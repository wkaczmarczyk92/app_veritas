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

use App\Models\LastLogin;

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
        // dd($request);
        $request->authenticate();
        $request->session()->regenerate();

        LastLogin::create([
            'user_id' => Auth::user()->id
        ]);

        if ($request->login_field == 'email') {
            $user = Auth::user();

            if ($user->hasRole(['admin', 'super-admin'])) {
                $redirect_to = RouteServiceProvider::ADMIN_HOME;
            } else if ($user->hasRole('recruiter')) {
                $redirect_to = RouteServiceProvider::RECRUITER_HOME;
            } else if ($user->hasRole('course_moderator')) {
                $redirect_to = RouteServiceProvider::COURSE_MODERATOR_HOME;
            }
        } else {
            $redirect_to = RouteServiceProvider::HOME;
        }
        // dd($redirect_to);

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
