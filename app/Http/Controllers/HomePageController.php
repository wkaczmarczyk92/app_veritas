<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Level;
use App\Models\PayoutRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use App\Models\Land;
use App\Services\Post\PostServiceGetter;
use App\Models\Language;


use App\Models\BonusStatus;
use App\Models\Offer;

// select user_profiles.first_name as imię, 
// user_profiles.last_name as nazwisko, 
// user_profiles.current_points as punkty, 
// users.pesel,
// user_has_bonuses.bonus_value as "wartość bonusu w €"
// from user_profiles 
// left join users on users.id = user_profiles.user_id 
// left join user_has_bonuses on user_has_bonuses.user_id = user_profiles.user_id
// where user_profiles.current_points >= 280
// and user_has_bonuses.completed = 0
// and user_profiles.last_name != 'pażdzioch'

class HomePageController extends Controller
{
    public function index()
    {
        $post_service_getter = new PostServiceGetter;
        $current_date = date('Y-m-d');

        $user = Auth::user();

        $recruiter_controller = new CRMRecruiterController;
        $recruiter = $recruiter_controller->show($user->user_profiles->crt_id_user_recruiter);

        return Inertia::render('User/Homepage/Homepage', [
            'levels' => Level::with(['checkpoints', 'multiplier', 'bonus_value'])->get(),
            'recruiter' => $recruiter->original,
            'posts' => $post_service_getter(),
            'payout_active' => (bool) PayoutRequest::with('user_has_bonus')
                ->whereHas('user_has_bonus', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->whereIn('bonus_status_id', BonusStatus::whereIn('name', ['in_progress', 'for_approval'])->pluck('id')->toArray());
                })->count(),
        ]);
    }

    public function index_free_account(Request $request)
    {
        // $filters = [];
        // $user_offers_id = Offer::where('user_id', auth()->id())->get()->pluck('crm_offer_id');
        // dd($user_offers);

        // dd(session('auth_mimic_uuid'));

        $post_service_getter = new PostServiceGetter;

        return Inertia::render('User/Homepage/HomepageForNoCRMAccount', [
            // 'lands' => Land::all(),
            'posts' => $post_service_getter(true),
            // 'languages' => Language::all(),
            // 'filters' => $filters,
            // 'user_offers_id' => $user_offers_id
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
