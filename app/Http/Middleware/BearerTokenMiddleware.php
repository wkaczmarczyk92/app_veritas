<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BearerTokenMiddleware
{
    private string $_token = 'U2BLi8rMhO78R2MaZvhvJG05fPGMGIUqlAWPLDhCAfCNAU16n0CkFqxT1ejG34Dh';
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        // var_dump($token);

        if (!$token or $token != $this->_token) {
            return response()->json([
                'success' => false,
                'msg' => 'auth failed'  
            ], 401);            
        }

        return $next($request);
    }
}
