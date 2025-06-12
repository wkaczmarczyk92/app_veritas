<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Helpers\CURLRequest;
use App\Helpers\Response;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\PointCheckpoint;

use App\Models\UserHasBonus;
use App\Models\LevelBonusValue;
use App\Models\BonusStatus;

class CheckPointsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/AdvanceSettings/PointsCheck');
    }

    public function check(Request $request)
    {
        // $current_year = date('Y');
        // $current_month = date('')
        $curl_request = new CURLRequest;
        $response = $curl_request->get_user_worked_days_in_previous_month([$request->pesel], $request->year, $request->month);

        if (! empty($response)) {
        } else {
            return Response::success('', [
                'output' => 'Brak dopasowań. Sprawdź czy PESEL jest prawidłowy.',
                'response' => $response
            ]);
        }

        ob_start();

        echo "{$request->year}-{$request->month}: przepracowanych dni -> " . $response->{$request->pesel};

        $output = ob_get_clean();

        return Response::success('', [
            'output' => $output,
            'response' => $response,
            'pesel' => $request->pesel,
            'points' => $response->{$request->pesel},
            'comment' => "Punkty za {$request->year}-{$request->month}"
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::with('user_profiles')->where('pesel', $request->pesel)->first();

            if ($user->user_profiles->level == 4) {
                return Response::danger('Użytkownik posiada już maksymalny poziom. Nie można mu przypisać więcej punktów.');
            }

            $user_points = UserPoint::create([
                'user_id' => $user->id,
                'points' => $request->points,
                'days' => $request->points,
                'auto' => false,
                'type' => 3,
                'comment' => $request->comment,
                'added_by_user_id' => Auth::id()
            ]);

            $user->user_profiles->total_points += $request->points;
            $user->user_profiles->total_days = $user->user_profiles->total_points;
            $user->user_profiles->current_points = $user->user_profiles->total_points;

            $current_user_level_id = $user->user_profiles->level;
            $point_checkpoint = PointCheckpoint::all();

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
                    }
                }
            }

            $user->user_profiles->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Coś poszło nie tak. Spóbuj ponownie później.', e: $e);
        }

        return Response::success('Punkty zostały przypisane do użytkownika.');
    }
}
