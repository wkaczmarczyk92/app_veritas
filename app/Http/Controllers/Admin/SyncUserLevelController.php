<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Helpers\Transaction;

use App\Models\User;
use App\Models\UserProfile;
use App\Helpers\Response;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\PointCheckpoint;
use App\Models\PayoutRequest;
use App\Models\BonusStatus;

use App\Helpers\CURLRequest;

class SyncUserLevelController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/AdvanceSettings/SyncUserLevel');
    }

    public function extract_phone_numbers_for_notifications() {}

    public function update(Request $request)
    {
        // return Response::success('', [
        //     'buffor' => 'Funkcja wyłączona.'
        // ]);

        ini_set('max_execution_time', 0); // Bez limitu
        ini_set('max_input_time', 0);     // Bez limitu na dane wejściowe
        ini_set('memory_limit', '-1'); // lub -1 dla braku limitu

        ob_start();

        if ($request->input('user_id')) {
            echo 'pojedynczy ziom';
        } else {
            DB::beginTransaction();
            $checkpoints = PointCheckpoint::with('level')->get();

            $users = User::with([
                'user_profiles',
                'user_profiles.levels',
                'user_points',
                'user_has_bonus',
                'user_has_bonus.status',
            ])
                ->whereHas('user_profiles', function ($query) {
                    $query->whereNotNull('crt_id_caretaker');
                })
                ->select('users.*')
                ->selectSub(function ($query) {
                    $query->from('user_points')
                        ->selectRaw('SUM(CASE 
                WHEN type IN (1, 3) THEN points
                WHEN type = 4 THEN -points
                ELSE 0 END)')
                        ->whereColumn('user_points.user_id', 'users.id');
                }, 'points_sum')
                ->get();

            // var_dump($users[0], $users[0]->user_profiles);
            echo 'Ilość wszystkich użytkowników - ';
            echo count($users);
            echo '<br>';
            echo '<br>';

            foreach ($users as $index => $user) {
                if ($user->points_sum == $user->user_profiles->total_points) {
                    unset($users[$index]);
                }
            }

            echo 'Ilość użytkowników wymagających synchronizacji - ';
            echo count($users);
            echo '<br><br><br>';
            $counter = 1;
            $curl_request = new CURLRequest;
            $pesels = $users->pluck('pesel');
            $response_may = $curl_request->get_user_worked_days_in_previous_month($pesels, '2025', '05');
            $response_june = $curl_request->get_user_worked_days_in_previous_month($pesels, '2025', '06');

            foreach ($users as $user) {
                // echo $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name;
                // echo '<br>';
                // echo 'Ilość punktów na profilu - ' . $user->user_profiles->total_points . '<br>';
                // echo 'Ilość punktów z historii - ' . $user->points_sum . '<br>';
                // echo 'Aktualny poziom - ' . $checkpoints->where('level_id', $user->user_profiles->level)->first()->level->name . '<br>';
                // echo 'Prawidłowy poziom - ' . $checkpoints->where('level_id', $this->correct_level($user->points_sum))->first()->level->name;
                // echo '<br><br>';

                if ($user->user_profiles->level != $this->correct_level($user->points_sum)) {
                    $current_user_has_bonus = null;
                    $current_user_has_bonus = $user->user_has_bonus->where('level_id', $user->user_profiles->level)->first();
                    $current_user_has_bonus->load('payout_request');

                    if ($current_user_has_bonus->bonus_status_id == 1) {
                        echo $counter++ . '<br>';
                        echo $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name;
                        echo '<br>';
                        echo 'Ilość punktów na profilu - ' . $user->user_profiles->total_points . '<br>';
                        echo 'Ilość punktów z historii - ' . $user->points_sum . '<br>';
                        echo 'Punkty za maj - ' . ($response_may->{$user->pesel} ?? 0) . '<br>';
                        echo 'Punkty za czerwiec - ' . ($response_june->{$user->pesel} ?? 0) . '<br>';
                        echo '<br>';
                        // echo 'Aktualny poziom - ' . $checkpoints->where('level_id', $user->user_profiles->level)->first()->level->name . '<br>';
                        // echo 'Prawidłowy poziom - ' . $checkpoints->where('level_id', $this->correct_level($user->points_sum))->first()->level->name;
                        // echo '<br><br>';


                        // echo 'Bonus użytkownika do skasowania - ID' . $current_user_has_bonus->id . '<br>';
                        // echo 'Wniosek o wypłatę do skasowania - ID' . ($current_user_has_bonus->payout_request ? 'sss-' . $current_user_has_bonus->payout_request->id : 'brak') . '<br>';
                        // echo 'Status wniosku o wypłatę - ' . $current_user_has_bonus->status->name_pl . '<br><br><br>';
                    }


                    // if ($current_user_has_bonus->bonus_status_id != 2 && $current_user_has_bonus->payout_request) {
                    //     $current_user_has_bonus->payout_request->delete();
                    // }

                    // if (isset($current_user_has_bonus) && $current_user_has_bonus && $current_user_has_bonus->bonus_status_id != 2) {
                    //     $current_user_has_bonus->delete();
                    // }
                }

                // $user->user_profiles->total_points = $user->points_sum;

                // if ($current_user_has_bonus->bonus_status_id == 2) {
                //     if ($user->user_profiles->level == 4) {
                //         $user->user_profiles->total_points = 700;
                //         echo 'Nowe dane zapisane.<br>';
                //         echo 'Nowe punkty użytkownika - ' . $user->user_profiles->total_points . '<br>';
                //     }
                // } else {
                //     $user->user_profiles->level = $this->correct_level($user->points_sum);
                // }

                // $user->user_profiles->current_points = $user->user_profiles->total_points;
                // $user->user_profiles->total_days = $user->user_profiles->total_points;
                // echo 'Poziom po zmianie - ' . $user->user_profiles->level;
                // $user->user_profiles->save();

                // echo '<br><br>';
            }

            // DB::commit();
        }

        $buffor = ob_get_clean();
        file_put_contents('syncs/synchronizacja_punktow_i_poziomow_' . date('YmdHis') . '.txt', $buffor);

        return Response::success('', [
            'buffor' => $buffor
        ]);
    }

    public function correct_level($points)
    {
        if ($points >= 700)
            return 4;

        if ($points >= 540)
            return 3;

        if ($points >= 280)
            return 2;

        return 1;
    }
}
