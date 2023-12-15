<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Offer;

use App\Services\MailService;
use App\Services\CRM\RecruiterService;

class CRONSendEmailWithOffers extends Command
{
    // TOKEN = HbwAJytaY515nTG8fFjXjKNgbQZwIh

    protected $signature = 'my:email-with-offers';

    public function handle()
    {
        $mail_service = new MailService();
        $recruiter_service = new RecruiterService();

        $users = User::with(['user_profiles', 'offers'])
            ->whereHas('offers', function($query) {
                $query->where('notification_send', false);
            })->get();

        $mail_service->offer_email($users, $recruiter_service);
        $offer_ids = $users->pluck('offers.*.id')->flatten();
        Offer::whereIn('id', $offer_ids)->update(['notification_send' => true]);
    }
}
