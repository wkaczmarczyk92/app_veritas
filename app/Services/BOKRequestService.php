<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\BOKRequest;

use App\Services\CRM\RecruiterService;

use App\Helpers\Response;
use App\Services\Banking\BankAccountStoreService;
use App\Models\Banking\BankAccount;
use App\Models\Banking\AccountType;

class BOKRequestService
{
    public function latest(int $take = 5)
    {
        return BOKRequest::with([
            'user.user_profiles',
            'subject'
        ])
            ->latest()
            ->take($take)
            ->get();
    }

    public function store(
        $request,
        User $user,
        MailService $mail_service,
        RecruiterService $recruiter_service,
        // BankAccountStoreService $bank_account_store_service
    ) {
        $user->load('user_profiles');

        DB::beginTransaction();

        if ($request['subject_id'] == 8 && empty($request['msg'])) {
            $request['msg'] = 'Zmiana numeru konta - automatycznie wygenerowany komentarz.';
        }

        try {
            $bok_request = BOKRequest::create([
                'user_id'       => $user->id,
                'subject_id'    => $request['subject_id'],
                'msg'           => $request['msg'],
            ]);

            if ($bok_request->subject_id == 8) {
                $bank_account = BankAccount::create([
                    'owner_name'        => $request['bank_account']['owner_name'],
                    'account_type_id'   => $request['bank_account']['account_type_id'],
                    'account_number'    => $request['bank_account']['account_number'],
                    'bank_name'         => $request['bank_account']['bank_name'],
                    'swift'             => $request['bank_account']['swift'],
                ]);

                $bok_request->bank_account()->attach($bank_account->id);

                $request['bank_account']['account_type'] = AccountType::find($request['bank_account']['account_type_id'])->type_pl;
            }

            $mail_service->bok_email(
                $request,
                $user,
                $recruiter_service->get(
                    $user->user_profiles->crt_id_user_recruiter
                )
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Wystapił błąd podczas połączenia. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Zgłoszenie zostało wysłane. Oczekuj na kontakt od naszego konsultanta w godzinach pracy 8 - 17 od pon - pt.');
    }
}
