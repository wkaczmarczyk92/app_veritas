<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show()
    {
        $user = User::with([
            'user_profiles',
            'user_points',
            'ready_to_departure_dates',
            'user_profile_image',
            'user_has_bonus'
        ])->find(Auth::user()->id);

        $user_with_crm_account = $user->user_profiles->crt_id_caretaker;

        $layout = $user_with_crm_account ? 'user' : 'free_account';

        return Inertia::render('User/Profile', [
            'user' => $user,
            'layout' => $layout,
            'user_with_crm_account' => $user_with_crm_account
        ]);
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
