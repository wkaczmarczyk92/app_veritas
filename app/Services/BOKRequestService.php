<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\BOKRequest;

use App\Services\CRM\RecruiterService;

class BOKRequestService
{
    public function latest(int $take = 5) {
        return BOKRequest::with([
            'user.user_profiles',
            'subject'])
                ->latest()
                ->take($take)
                ->get();
    }

    public function store(
            int $subject_id,
            string $msg,
            User $user,
            MailService $mail_service,
            RecruiterService $recruiter_service) {
        $user->load('user_profiles');

        DB::beginTransaction();

        try {
            BOKRequest::create([
                'user_id'       => $user->id,
                'subject_id'    => $subject_id,
                'msg'           => $msg,
            ]);

            $mail_service->bok_email(
                $subject_id,
                $msg,
                $user,
                $recruiter_service->get(
                    $user->user_profiles->crt_id_user_recruiter
                ));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success'       => false,
                'msg'           => 'Wystapił błąd podczas połączenia. Spróbuj ponownie później.',
                'exception'     => $e->getMessage(),
            ];
        }

        return [
            'success'   => true,
            'msg'       => 'Zgłoszenie zostało wysłane. Oczekuj na kontakt od naszego konsultanta w godzinach pracy 8 - 17 od pon - pt.',
        ];
    }

}
