<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPoint;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use Exception;

use Inertia\Inertia;
use App\Models\PointCheckpoint;

use Illuminate\Validation\ValidationException;

use App\Models\UserHasBonus;
use App\Models\LevelBonusValue;
use Database\Seeders\LevelSeeder;
use Illuminate\Support\Facades\Redirect;

use App\Models\PayoutRequest;
use App\Services\UserPointService;

class UserPointController extends Controller
{
    protected UserPointService $user_point_service;

    public function __construct(UserPointService $user_point_service)
    {
        $this->user_point_service = $user_point_service;
    }


    public function index(Request $request)
    {
        DB::enableQueryLog();
        $user_points = UserPoint::where('user_id', $request->user_id)
            ->latest()
            ->offset(10 * ($request->current_page - 1))
            ->limit(10)
            ->get();

        $queries = DB::getQueryLog();
        // dd($queries);

        return response()->json([
            'user_points' => $user_points,
            'queries' => $queries
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'value' => 'required|numeric|min:1',
                'type' => 'required|numeric',
                'comment' => 'nullable|string',
                'user_id' => 'required|numeric'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()]);
        }

        DB::enableQueryLog();
        DB::beginTransaction();

        try {
            $user_profile = UserProfile::where('user_id', '=', $request->user_id)->first();

            if ($request->type == 4) {
                $is_current_lower_than_zero = ($user_profile->current_points - $request->value) < 0 ? true : false;
                if ($is_current_lower_than_zero) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'value' => ["Różnica między aktualną a odejmowaną liczbą punktów nie może być mniejsza niż zero. Maksymalna ilość punktów do odjęcia - {$user_profile->current_points}."]
                        ]
                    ]);
                }
            }

            $user_profile->total_points = $request->type == 3 ? ($user_profile->total_points + $request->value) : ($user_profile->total_points - $request->value);
            $user_profile->current_points = $request->type == 3 ? ($user_profile->current_points + $request->value) : ($user_profile->current_points - $request->value);

            $point_checkpoint = PointCheckpoint::all();

            $current_user_level_id = $user_profile->level;

            foreach ($point_checkpoint as $checkpoint) {
                if ($checkpoint->checkpoint <= $user_profile->total_points) {
                    $user_profile->level = $checkpoint->level_id;

                    if ($current_user_level_id < $user_profile->level) {
                        $user_has_bonuse = UserHasBonus::create([
                            'user_id' => $request->user_id,
                            'level_id' => $user_profile->level,
                            'bonus_value' => LevelBonusValue::where('level_id', $user_profile->level)->pluck('value')[0],
                            'bonus_status_id' => 5
                        ]);
                    }
                }
            }

            $user_profile->save();

            $result = $user_point = UserPoint::create([
                'user_id' => $request->user_id,
                'points' => $request->value,
                'auto' => false,
                'type' => $request->type,
                'comment' => $request->comment,
                'added_by_user_id' => Auth::user()->id
            ]);

            // dd($result);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'record' => $user_point,
            'user_profiles' => $user_profile
        ]);
    }

    public function last_insert_date()
    {
        return response()->json(['last_insert_date' => $this->user_point_service->get_last_insert_date()]);
    }

    public function activate_by_admin(int $user_id)
    {
        try {
            DB::beginTransaction();

            $user_bonus = UserHasBonus::where('user_id', $user_id)->where('bonus_status_id', 5)->get();

            foreach ($user_bonus as $bonus) {
                $bonus->bonus_status_id = 1;
                $bonus->save();

                PayoutRequest::create([
                    'user_has_bonus_id' => $bonus->id,
                    'payout_value' => $bonus->bonus_value,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return back()->withErrors(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Bonusy zostały aktywowane.',
            'alert_type' => 'success'
        ]);
    }
}
