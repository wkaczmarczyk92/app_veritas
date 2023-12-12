<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\User;


use App\Models\Multiplier;
use App\Models\Level;


use App\Models\PointsSettingsPayout;
use App\Models\CashSettingsPayout;

use App\Models\PayoutRequest;
use App\Models\BOKRequest;

use App\Models\PointCheckpoint;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with(['user_profiles', 'roles'])
            ->whereHas('roles', function($query) {
                $query->where('id', 2);
            })
            ->latest()
            ->take(10)
            ->get();

        $latest_payout_requests = PayoutRequest::with('user_has_bonus.user.user_profiles')
            ->whereHas('user_has_bonus', function($query) {
                $query->where('completed', false)
                    ->where('in_progress', true);
            })
            ->latest()
            ->take(5)
            ->get();

        $latest_bok_request = BOKRequest::with(['user.user_profiles', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Admin/Home', [
            'users' => $users,
            'levels' => Level::with(['multiplier', 'checkpoints', 'bonus_value'])->get(),
            'payout_cash' => CashSettingsPayout::where('id', '=', 1)->pluck('payout_cash')[0],
            'points_to_payout' => PointsSettingsPayout::where('id', '=', 1)->pluck('points_to_payout')[0],
            'latest_payout_requests' => $latest_payout_requests,
            'latest_bok_request' => $latest_bok_request,
            // 'point_chekpoints' => PointCheckpoint::all()
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
