<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Level;

use App\Http\Controllers\CRMRecruiterController;
use App\Models\Post;
use App\Models\PayoutRequest;

use App\Models\Bonus;
use Illuminate\Support\Facades\DB;

use App\Models\UserHasBonus;

class HomePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $current_date = date('Y-m-d');
        $user = User::with(['user_profiles', 'user_points', 'ready_to_departure_dates', 'user_profile_image', 'user_has_bonus' => function($query) {
            // $query->where('completed', false)
            //     ->where('in_progress', false);
        }])->find(Auth::user()->id);

        // dd($user->user_has_bonus);
        
        $recruiter_controller = new CRMRecruiterController;
        $recruiter = $recruiter_controller->show($user->user_profiles->crt_id_user_recruiter);
        // DB::enableQueryLog();

        $posts = Post::with('post_labels')
            ->where('type', '=', 'publish')
            ->where(function($query) use ($current_date) {

                $query->where(function($query) use ($current_date) {
                    $query->whereNull('start_at')
                        ->whereNull('end_at');
                });

                $query->orWhere(function($query) use ($current_date) {
                    $query->where('start_at', '<=', $current_date)
                        ->where('end_at', '>=', $current_date);
                });

                $query->orWhere(function($query) use ($current_date) {
                    $query->whereNull('start_at')
                        ->where('end_at', '>=', $current_date);
                });

                $query->orWhere(function($query) use ($current_date) {
                    $query->whereNull('end_at')
                        ->where('start_at', '<=', $current_date);
                });
            })
            ->orderBy('order')
            ->get();
        // $queries = DB::getQueryLog();
        // dd($queries);

        // $user_id = $user->id;

        return Inertia::render('Homepage/Homepage', [
            'user' => $user,
            'levels' => Level::with(['checkpoints', 'multiplier', 'bonus_value'])->get(),
            'recruiter' => $recruiter->original,
            'posts' => $posts,
            'payout_active' => (bool)PayoutRequest::with('user_has_bonus')
                ->whereHas('user_has_bonus', function($query) use ($user) {
                    $query->where('completed', false)
                        ->where('in_progress', true)
                        ->where('user_id', $user->id);
                })->count(),
            'bonus' => [
                'family_recommendation' => Bonus::where('name', 'family_recommendation')->pluck('bonus_value')[0],
                'caretaker_recommendation' => Bonus::where('name', 'caretaker_recommendation')->pluck('bonus_value')[0],
            ]
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
