<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Response;

class AuthMimicController extends Controller
{
    public function login_as_user(Request $request)
    {
        if (!Auth::user()->uuid) {
            return Response::danger('Nie masz uprawnień do tej akcji.');
        }

        $mimic_uuid = Auth::user()->uuid;

        $user = User::find($request->user_id);
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::login($user);

        session([
            'auth_mimic_uuid' => $mimic_uuid,
        ]);

        if ($user->email) {
            if ($user->hasRole(['admin', 'super-admin', 'god_mode'])) {
                $redirect_to = RouteServiceProvider::ADMIN_HOME;
            } else if ($user->hasRole('recruiter')) {
                $redirect_to = RouteServiceProvider::RECRUITER_HOME;
            } else if ($user->hasRole('course_moderator')) {
                $redirect_to = RouteServiceProvider::COURSE_MODERATOR_HOME;
            } else if ($user->hasRole('bok')) {
                $redirect_to = RouteServiceProvider::ADMIN_HOME;
            }
        } else {
            $user->load('user_profiles');
            if ($user->user_profiles->crt_id_caretaker !== null) {
                $redirect_to = RouteServiceProvider::HOME;
            } else {
                $redirect_to = 'home.index_free_account';
            }
        }

        return Response::success('Nastąpi przelogowanie.', [
            'redirect_to' => $redirect_to
        ]);
    }

    public function back_to_admin_panel() {
        $user = User::where('uuid', session('auth_mimic_uuid'))->first();
        session()->forget('auth_mimic_uuid');

        if (!$user) {
            return Response::danger('Nie znaleziono użytkownika.');
        }

        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        Auth::login($user);

        return Response::success('Nastąpi powrót do panelu admin.');
    }
}
