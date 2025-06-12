<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\SMS;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CRONSendSMSWithPassword extends Command
{
    protected $signature = 'cron:sms-with-password';

    public function handle()
    {
        set_time_limit(0);
        $today = date('Y-m-d');

        $users = User::with(['user_profiles', 'password_for_user'])
            ->whereHas('password_for_user', function ($query) use ($today) {
                $query->where('sent', false)
                    ->where('departure_date', '<=', $today);
            })->get();

        // $password = Str::random();
        // $sms = new SMS;
        // $sms->params['to'] = '+48723864128';
        // $sms->params['message'] = "Dzień dobry,\nponiżej znajduje się automatycznie wygenerowane hasło, za pomocą którego można zalogować się do aplikacji Veritas:\n\nLink do aplikacji - https://app.veritas.pl/login\nHasło - {$password}\n\nPozdrawiamy,\nzespół Veritas";
        // $result = $sms->send();

        // var_dump($result);

        foreach ($users as $user) {
            unset($password);
            $password = Str::random();
            $sms = new SMS;
            $sms->params['to'] = $user->user_profiles->phone_number;
            $sms->params['message'] = "Dzień dobry,\nponiżej znajduje się automatycznie wygenerowane hasło, za pomocą którego można zalogować się do aplikacji Veritas:\n\nLink do aplikacji - https://app.veritas.pl/login\nHasło - {$password}\n\nPozdrawiamy,\nzespół Veritas";
            $result = $sms->send();

            // var_dump($sms->params);
            // echo '<br><br>';

            if ($result) {
                $user->password_for_user->sent = true;
                $user->password = Hash::make($password);
                $user->save();
                $user->password_for_user->save();
            }
        }
    }
}
