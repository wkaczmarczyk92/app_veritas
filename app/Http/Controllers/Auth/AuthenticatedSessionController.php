<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\Response as ResponseHelper;

use App\Models\LastLogin;
use App\Models\Token;
use Illuminate\Support\Str;
use App\Helpers\SMS;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user->active) {
            $user->load(['tokens' => function ($query) {
                $query->whereIn('token_type_id', [1, 2])->where('active', true);
            }], 'user_profiles');

            // dd($user);

            if (count($user->tokens) != 2) {
                $user->load(['tokens' => function ($query) {
                    $query->whereIn('token_type_id', [1, 2]);
                }]);

                foreach ($user->tokens as $token) {
                    $token->active = false;
                    $token->save();
                }

                do {
                    $token = rand(100000, 999999);
                } while (Token::where('token', $token)->exists());

                $register_token = Token::create([
                    'token' => $token,
                    'token_type_id' => 1,
                ]);

                $user->tokens()->save($register_token);

                do {
                    $token = Str::random(150);
                } while (Token::where('token', $token)->exists());

                $register_url_token = Token::create([
                    'token' => $token,
                    'token_type_id' => 2,
                ]);

                $user->tokens()->save($register_url_token);

                $sms = new SMS;
                $sms->params['to'] = $user->user_profiles->phone_number;
                $sms->params['message'] = "Twój kod do aktywacji konta: {$register_token->token}";

                $sms->send();
            } else {
                $code = $user->tokens->filter(fn($item) => $item->token_type_id == 1)->values()[0];
                $register_url_token = $user->tokens->filter(fn($item) => $item->token_type_id == 2)->values()[0];

                $sms = new SMS;
                $sms->params['to'] = $user->user_profiles->phone_number;
                $sms->params['message'] = "Twój kod do aktywacji konta: {$code->token}";

                $sms->send();
            }
            $user_id = $user->id;
            Auth::logout();  // Wylogowanie użytkownika
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $response = [
                'email' => 'Twoje konto jest nieaktywne. Skontaktuj się z administratorem.',
                'token' => $register_url_token->token,
                'p' => $user_id
            ];

            return redirect()->route('login')->withErrors($response);
        }


        LastLogin::create([
            'user_id' => $user->id
        ]);

        if ($request->login_field == 'email') {
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

        // $redirect_to = $request->login_field == 'email' ? RouteServiceProvider::ADMIN_HOME : RouteServiceProvider::HOME;

        return redirect()->route($redirect_to);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // dd($request);

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
