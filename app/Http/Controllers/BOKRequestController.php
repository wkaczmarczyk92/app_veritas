<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BOKRequest;
use App\Models\BOKSubject;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\BOKEmail;
use App\Models\User;

// use App\Http\Controllers\CRMRecruiterController;
use App\Services\RecruiterService;

use Exception;


class BOKRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bok_reuqests = BOKRequest::with(['user.user_profiles', 'subject'])
            ->paginate(5);

        return Inertia::render('Admin/BOKRequests', [
            'bok_requests' => $bok_reuqests
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
            $validation = $request->validate([
                'subject_id' => 'required|integer|min:1',
                'msg' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ]);
        }  
        
        try {
            DB::beginTransaction();
            $user = User::with('user_profiles')->where('id', Auth::user()->id)->first();

            BOKRequest::create([
                'user_id' => $user->id,
                'subject_id' => $request->subject_id,
                'msg' => $request->msg
            ]);

            $subject = BOKSubject::where('id', $request->subject_id)->pluck('subject')[0];
            $email_data = [
                'subject' => $subject,
                'msg' => $request->msg,
                'username' => $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name,
                'url' => "http://app.veritas.pl/uzytkownik/{$user->id}",
                'url_crm' => "https://local.grupa-veritas.pl/#/opiekunki/{$user->user_profiles->crt_id_caretaker}"
            ];

            Mail::to(BOKEmail::$email)->send(
                new BOKEmail($email_data)
            );
            
            // Mail::to('w.kaczmarczyk@grupa-veritas.pl')->send(
            //     new BOKEmail($email_data)
            // );

            $recruiter_service = new RecruiterService;
            $recruiter = $recruiter_service->get($user->user_profiles->crt_id_user_recruiter);

            if (!empty($recruiter->usr_email) and filter_var($recruiter->usr_email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($recruiter->usr_email)->send(
                    new BOKEmail($email_data)
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => 'Wystapił błąd podczas połączenia. Spróbuj ponownie później.',
                'exception' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Zgłoszenie zostało wysłane. Oczekuj na kontakt od naszego konsultanta w godzinach pracy 8 - 17 od pon - pt.'
        ]);
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
