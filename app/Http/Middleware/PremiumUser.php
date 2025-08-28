<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class PremiumUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $user->load('user_profiles');

        if ($user->user_profiles == null || $user->user_profiles->crt_id_caretaker === null) {
            return redirect()->route('home.index_free_account');
        }

        return $next($request);
    }
}
