<?php

namespace App\Services\Admin\User;

use App\Models\UserPoint;
use App\Models\User;
use App\Helpers\Transaction;
use App\Helpers\CURLRequest;
use App\Models\PointCheckpoint;
use App\Models\BonusStatus;
use App\Models\LevelBonusValue;

class SyncUserPointsService
{
    private $_start_year = 2023;
    private $_start_month = 1;

    public function __invoke($user_id)
    {
        set_time_limit(0);

        $user = User::with([
            'user_profiles',
            'user_points'
        ])->find($user_id);

        $curl_request = new CURLRequest;

        $current = new \DateTimeImmutable('first day of this month');
        $end = ((int)date('d') >= 16)
            ? $current->modify('-1 month')
            : $current->modify('-2 months');

        $end_year  = (int)$end->format('Y');
        $end_month = (int)$end->format('n');

        return Transaction::try(function () use ($user, $end_year, $end_month, $curl_request) {
            UserPoint::where('user_id', $user->id)->where('auto', false)->delete();
            $user->user_profiles->level = 1;
            $user->user_profiles->total_points = 0;
            $user->user_profiles->current_points = 0;
            $user->user_profiles->total_days = 0;
            $user->user_profiles->save();

            $point_checkpoint = PointCheckpoint::all();

            for ($i = $this->_start_year; $i <= $end_year; $i++) {
                $to_m   = ($i === $end_year) ? $end_month : 12;

                for ($j = 1; $j <= $to_m; $j++) {
                    $month = strlen($j) === 1 ? "0{$j}" : "$j";
                    $year = "$i";
                    $response = $curl_request->get_user_worked_days_in_previous_month([$user->pesel], $year, $month);

                    if ($response) {
                        $response = (array)$response;

                        $user->user_profiles->total_points += $response[$user->pesel];
                        $user->user_profiles->current_points += $response[$user->pesel];
                        $user->user_profiles->total_days += $response[$user->pesel];

                        UserPoint::create([
                            'user_id' => $user->id,
                            'points' => $response[$user->pesel],
                            'days' => $response[$user->pesel],
                            'auto' => true,
                            'type' => 1,
                            'comment' => "Punkty za $month-$year"
                        ]);

                        foreach ($point_checkpoint as $checkpoint) {
                            if ($checkpoint->checkpoint <= $user->user_profiles->total_points) {
                                $user->user_profiles->level = $checkpoint->level_id;
                            }
                        }
                    }
                }
            }

            $user->user_profiles->save();
            $user->load([
                'user_points' => function ($query) {
                    $query->latest()
                        ->offset(0)
                        ->limit(10)
                        ->get();
                    }
                ]);

            return [
                'user_profiles' => $user->user_profiles,
                'user_points' => $user->user_points
            ];
        }, 'Punkty użytkownika zostały zsynchronizowane.');
    }
}
