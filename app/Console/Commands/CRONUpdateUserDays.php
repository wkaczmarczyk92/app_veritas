<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

use App\Helpers\CURLRequest;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\PointCheckpoint;
use App\Models\UserPoint;

use App\Models\UserHasBonus;
use App\Models\LevelBonusValue;


use App\Helpers\SMS;
use App\Models\BonusStatus;

class CRONUpdateUserDays extends Command
{
    protected $signature = 'cron:update-user-days';


    public function handle()
    {
        set_time_limit(0);

        $current_date = date('Y-m-d');
        $year = date('Y', strtotime($current_date . " -1 month"));
        $month = date('m', strtotime($current_date . " -1 month"));
        // $year = '2023';
        // $month = '07';

        $curl_request = new CURLRequest;

        // // możliwe że trzeba podzielić - po 5k użytkowników
        $users = User::with(['user_profiles.levels.checkpoints', 'user_profiles.levels.multiplier', 'user_has_bonus'])
            ->role('user')
            ->get();

        $pesels = [];

        foreach ($users as $user) {
            $pesels[] = $user->pesel;
        }

        $response = $curl_request->get_user_worked_days_in_previous_month($pesels, $year, $month);

        DB::beginTransaction();

        try {
            foreach ($response as $pesel => $days) {
                $user = User::with(['user_profiles.levels.checkpoints', 'user_profiles.levels.multiplier', 'user_has_bonus'])
                    ->where('pesel', $pesel)
                    ->first();

                if ($user->user_profiles->level >= 4) {
                    continue;
                }

                $point_checkpoint = PointCheckpoint::all();

                $user->user_profiles->total_days += $days;
                $user->user_profiles->total_points += $days;
                $user->user_profiles->current_points += $days;

                $current_user_level_id = $user->user_profiles->level;

                foreach ($point_checkpoint as $checkpoint) {
                    if ($checkpoint->checkpoint <= $user->user_profiles->total_points) {
                        $user->user_profiles->level = $checkpoint->level_id;

                        if ($current_user_level_id < $user->user_profiles->level) {
                            $user_has_bonus = UserHasBonus::create([
                                'user_id' => $user->id,
                                'level_id' => $user->user_profiles->level,
                                'bonus_value' => LevelBonusValue::where('level_id', $user->user_profiles->level)->pluck('value')[0],
                                'bonus_status_id' => BonusStatus::where('name', 'available')->value('id')
                            ]);

                            $sms = new SMS;
                            $sms->set_time();
                            $sms->params['to'] = $user->user_profiles->phone_number;
                            $sms->params['message'] = "Gratulujemy! Osiągnąłeś/aś nowy poziom. Odbierz swój bonus w wysokości {$user_has_bonus->bonus_value}€ w aplikacji Veritas.";

                            $sms->send();
                            unset($sms);
                        }
                    }
                }

                $user_point = UserPoint::create([
                    'user_id' => $user->id,
                    'points' => $days,
                    'days' => $days,
                    'auto' => true,
                    'type' => 1,
                    'comment' => "Punkty za $month-$year"
                ]);

                $user->user_profiles->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            echo json_encode([
                'success' => false,
                'msg' => '',
                'exception' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'msg' => 'Udało się.',
            'exception' => ''
        ]);
    }
}
