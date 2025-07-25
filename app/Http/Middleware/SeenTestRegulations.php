<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeenTestRegulations
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $user->load(['seen_infos' => function ($query) {
            $query->where('info_type', 'become_mittel_program');
        }]);

        if ($user->seen_infos->isEmpty()) {
            return redirect()->route('user.german.lessons.become.mittel.program');
        }

        return $next($request);
    }
}
