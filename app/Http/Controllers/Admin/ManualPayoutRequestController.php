<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PayoutRequestsExport;
use App\Helpers\CaretakerAgreementGetter;
use App\Mail\ExportPayoutRequestsEmail;
use App\Models\BonusStatus;
use App\Models\UserHasBonus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use App\Helpers\Response;

class ManualPayoutRequestController
{
    public function index()
    {
        return Inertia::render('Admin/AdvanceSettings/ManualPayoutRequest');
    }

    public function update()
    {
        set_time_limit(0);

        $user_has_bonuses = UserHasBonus::with([
            'user' => function ($query) {
                $query->select('id', 'pesel');
            },
            'user.user_profiles' => function ($query) {
                $query->select('user_id', 'first_name', 'last_name', 'phone_number', 'crt_id_caretaker');
            }
        ])
            ->where('bonus_status_id', BonusStatus::where('name', 'in_progress')->value('id'))
            ->get();

        $data = [];
        $i = 0;

        DB::beginTransaction();

        $completed_id = BonusStatus::where('name', 'completed')->value('id');

        try {
            foreach ($user_has_bonuses as $user) {
                $caretaker_agreement_getter = new CaretakerAgreementGetter;

                $data[$i]['no'] = $i + 1;
                $data[$i]['pesel'] = $user->user->pesel;
                $data[$i]['first_name'] = $user->user->user_profiles->first_name;
                $data[$i]['last_name'] = $user->user->user_profiles->last_name;
                $data[$i]['phone_number'] = $user->user->user_profiles->phone_number;
                $data[$i]['bonus_value'] = $user->bonus_value . ' €';
                $data[$i]['company_name'] = $caretaker_agreement_getter->get($user->user->user_profiles->crt_id_caretaker);
                $i++;

                $user->bonus_status_id = $completed_id;
                $user->save();
            }

            usort($data, function ($a, $b) {
                return $a['company_name'] <=> $b['company_name'];
            });

            $filename = date('Ymd') . "_zlecenia_wyplaty_bonusow2.xlsx";

            Excel::store(new PayoutRequestsExport($data, $i + 1), $filename, 'exports');

            if (app()->environment('production')) {
                Mail::to(ExportPayoutRequestsEmail::$emails)
                    ->cc(ExportPayoutRequestsEmail::$cc_emails)
                    ->send(new ExportPayoutRequestsEmail($filename));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/' . date('YmdHis') . '.log'),
            ])->info(
                'Błąd podczas generowania pliku zlecenia wypłat bonusów. 
                Exception:' . $e->getMessage() . '
                Linia: ' . $e->getLine() . '
                Plik: ' . $e->getFile()
            );

            return Response::danger('', [
                'output' => 'Błąd podczas generowania pliku zlecenia wypłat bonusów. Sprawdź logi.',
            ]);
        }

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/' . date('YmdHis') . '.log'),
        ])->info('Plik został wygenerowany. Nazwa pliku: ' . $filename);

        return Response::success('', [
            'output' => 'Plik został wygenerowany. Nazwa pliku: ' . $filename,
        ]);
    }
}
