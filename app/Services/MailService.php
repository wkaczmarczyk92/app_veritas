<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

use App\Mail\BOKEmail;
use App\Mail\OfferEmail;

use App\Models\User;
use App\Models\BOKSubject;

class MailService
{
    public function bok_email($subject_id, $msg, User $user, $recruiter = null) : void {
        $subject = BOKSubject::where('id', $subject_id)->first();

        if ($subject == null) {
            throw new \Exception('Subject not found.');
        }

        $email_data = [
            'subject'   => $subject->subject,
            'msg'       => $msg,
            'username'  => $user->user_profiles->first_name.' '.$user->user_profiles->last_name,
            'url'       => "http://app.veritas.pl/uzytkownik/{$user->id}",
            'url_crm'   => "https://local.grupa-veritas.pl/#/opiekunki/{$user->user_profiles->crt_id_caretaker}",
        ];

        $emails = [];

        if (app()->environment() == 'production') {
            $emails[] = BOKEmail::$email;

            if ($recruiter != null and !empty($recruiter->usr_email) and filter_var($recruiter->usr_email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $recruiter->usr_email;
            }
        } else {
            $emails[] = Mail::$local_email;
        }

        foreach ($emails as $email) {
            Mail::to($email)->send(
                new BOKEmail($email_data)
            );
        }
    }

    public function offer_email($users, $recruiter_service) {
        foreach ($users as $user) {
            $email = app()->environment() == 'production' ? $recruiter_service->get($user->user_profiles->crt_id_user_recruiter)->usr_email : Mail::$local_email;

            $email_data = [
                'first_name' => $user->user_profiles->first_name,
                'last_name' => $user->user_profiles->last_name,
                'offers' => [
                    $user->offers
                ],
                'caretaker_crm_url' => 'https://local.grupa-veritas.pl/#/opiekunki/' . $user->user_profiles->crt_id_caretaker,
                'caretaker_app_url' => 'http://app.veritas.pl/uzytkownik/' . $user->id,
            ];

            Mail::to($email)->send(
                new OfferEmail($email_data)
            );
        }
    }
}
