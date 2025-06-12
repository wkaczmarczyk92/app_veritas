<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\ContactForm;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Mail\ContactFormEmail;
use Illuminate\Support\Facades\Mail;

use App\Services\CRM\RecruiterService;

class ContactFormController extends Controller
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
        try {
            $validate = $request->validate([
                'subject' => 'nullable|string',
                'msg' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()]);
        }

        $contact_form = ContactForm::create([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'msg' => $request->msg
        ]);

        $user = User::with('user_profiles')->find(Auth::user()->id);

        if (app()->environment('production')) {
            $data = [
                'subject' => $request->subject,
                'msg' => $request->msg,
                'username' => $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name,
                'url' => "https://app.veritas.pl/uzytkownik/{$user->id}",
                'url_crm' => "https://local.grupa-veritas.pl/#/opiekunki/{$user->user_profiles->crt_id_caretaker}"
            ];

            Mail::to(ContactFormEmail::$email)->send(
                new ContactFormEmail($data)
            );

            $recruiter_service = new RecruiterService;
            $recruiter = $recruiter_service->get($user->user_profiles->crt_id_user_recruiter);

            if (!empty($recruiter->usr_email) and filter_var($recruiter->usr_email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($recruiter->usr_email)->send(
                    new ContactFormEmail($data)
                );
            }
        }

        return response()->json([
            'success' => true
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
