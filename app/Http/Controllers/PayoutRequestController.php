<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserProfile;
use App\Models\UserPoint;
use App\Models\PayoutRequest;
use App\Models\UserHasBonus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Exception;


use Illuminate\Support\Facades\Mail;
use App\Mail\PayoutRequestEmail;

use App\Models\Level;
use App\Models\User;

class PayoutRequestController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/PayoutRequests', [
            'levels' => Level::all()
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
        DB::beginTransaction();
        $user_id = Auth::user()->id;

        $user = User::with('user_profiles')->find($user_id);

        try {
            foreach ($request->user_has_bonus as $bonus) {
                PayoutRequest::create([
                    'payout_value' => $bonus['bonus_value'],
                    'user_has_bonus_id' => $bonus['id']
                ]);

                $uhb = UserHasBonus::find($bonus['id']);
                $uhb->in_progress = true;
                $uhb->save();

                if (app()->environment() == 'production') {
                    $email_data = [
                        'username' => $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name,
                        'level' => Level::where('id', $uhb->level_id)->pluck('name'),
                        'bonus_value' => $bonus['bonus_value'],
                        'url' => "http://app.veritas.pl/uzytkownik/{$user->id}",
                        'url_crm' => "https://local.grupa-veritas.pl/#/opiekunki/{$user->user_profiles->crt_id_caretaker}"
                    ];

                    Mail::to(PayoutRequestEmail::$email)->send(
                        new PayoutRequestEmail($email_data)
                    );
                }
            }

            DB::commit();
        } catch (Exception $e) {
            // dd($th->getMessage());
            DB::rollback();
            return response()->json([
                'success' => false,
                'exception' => $e->getMessage()
            ]);
        }

        return response()->json(['success' => true]);
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
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->to_update as $id) {
                $payout_request = PayoutRequest::with('user_has_bonus')->find($id);
                // $payout_request->payment_completed = true;
                $payout_request->user_id_completed_by = Auth::user()->id;
                $payout_request->save();

                $payout_request->user_has_bonus->completed = true;
                $payout_request->user_has_bonus->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'exception' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
