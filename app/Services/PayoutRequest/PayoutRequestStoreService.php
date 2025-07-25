<?php

namespace App\Services\PayoutRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PayoutRequest;
use App\Models\UserHasBonus;
use App\Models\BonusStatus;
use App\Helpers\Response;
use App\Services\CRM\DocumentService;
use App\Models\CRM\CaretakerBlackList;
use App\Helpers\SMS;
use App\Mail\PayoutRequestEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Level;
use Exception;

class PayoutRequestStoreService
{
    public function __invoke($request)
    {
        DB::beginTransaction();
        $user_id = Auth::user()->id;

        $user = User::with('user_profiles')->find($user_id);

        if (app()->environment('production')) {
            $document_service = new DocumentService;
            $caretaker_docs = $document_service->full($user->user_profiles->crt_id_caretaker);

            $is_registered = (
                $caretaker_docs->assignments
                && $caretaker_docs->assignments[0]
                && $caretaker_docs->assignments[0]->contract
                && $caretaker_docs->assignments[0]->contract->registration
                && $caretaker_docs->assignments[0]->contract->registration->reg_id_registration_status == 1
                && $caretaker_docs->assignments[0]->contract->assignment->ssg_canceled == 0
            );

            if (!$is_registered) {
                return Response::danger('Aktualnie nie jesteś zarejestrowana jako pracownik firmy Veritas. Aby wypłacić bonus skontaktuj się ze swoim rekruterem.');
            }

            // sprawdzenie czy użytkownik jest na czarnej liście
            // i ustawnienie odpowiedniego statusu
            $caretaker_black_list = CaretakerBlackList::where('cbh_id_caretaker', $user->user_profiles->crt_id_caretaker)->latest('cbh_created')->first();
            $black_list = false;

            if ($caretaker_black_list and $caretaker_black_list->cbh_id_status == 1) {
                $status_id = BonusStatus::where('name', 'for_approval')->value('id');
                $black_list = true;
            } else {
                $status_id = BonusStatus::where('name', 'in_progress')->value('id');
            }
        } else {
            $black_list = false;
            $status_id = BonusStatus::where('name', 'in_progress')->value('id');
        }

        try {
            foreach ($request->user_has_bonus as $bonus) {
                $payout_request = new PayoutRequest([
                    'payout_value' => $bonus['bonus_value'],
                    'user_has_bonus_id' => $bonus['id']
                ]);

                if ($black_list) {
                    $payout_request->black_list_comment = $caretaker_black_list->cbh_comment;
                }

                $payout_request->save();

                $uhb = UserHasBonus::find($bonus['id']);
                $uhb->bonus_status_id = $status_id;

                $uhb->save();

                // if (app()->environment() == 'production') {
                //     $email_data = [
                //         'username' => $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name,
                //         'level' => Level::where('id', $uhb->level_id)->pluck('name'),
                //         'bonus_value' => $bonus['bonus_value'],
                //         'url' => "https://app.veritas.pl/uzytkownik/{$user->id}",
                //         'url_crm' => "https://local.grupa-veritas.pl/#/opiekunki/{$user->user_profiles->crt_id_caretaker}"
                //     ];

                //     Mail::to(PayoutRequestEmail::$email)->send(
                //         new PayoutRequestEmail($email_data)
                //     );
                // }
            }

            if (app()->environment('production')) {
                if (!$black_list) {
                    $sms = new SMS;
                    $sms->params['to'] = $user->user_profiles->phone_number;
                    $sms->params['message'] = "Twój bonus został zlecony do wypłaty i powinien być przelany maksymalnie do 15 dnia kolejnego miesiąca. W razie pytań zapraszamy do kontaktu pod numerem 71 75 88 140";

                    $result = $sms->send();

                    if ($result !== true) {
                        throw new Exception('SMS failed. ' . $result);
                    }
                }

                $email_data = [
                    'username' => $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name,
                    'level' => Level::where('id', $uhb->level_id)->pluck('name'),
                    'bonus_value' => $bonus['bonus_value'],
                    'url' => "https://app.veritas.pl/uzytkownik/{$user->id}",
                    'url_crm' => "https://local.grupa-veritas.pl/#/opiekunki/{$user->user_profiles->crt_id_caretaker}"
                ];

                Mail::to(PayoutRequestEmail::$email)->send(
                    new PayoutRequestEmail($email_data)
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Twój wniosek o wypłatę bonusu został złożony.');
    }
}
