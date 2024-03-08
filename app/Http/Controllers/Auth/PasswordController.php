<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use Inertia\Inertia;

use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        try {
            $validate = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->errors()
            ]);
        }

        try {
            $request->user()->update([
                'password' => Hash::make($validate['password']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'alert_type' => 'danger',
                'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.'
            ]);
        }

        return response()->json([
            'success' => true,
            'alert_type' => 'success',
            'msg' => 'Hasło zostało zmienione.'
        ]);
    }

    public function edit()
    {
        return Inertia::render('User/PasswordChange');
    }
}
