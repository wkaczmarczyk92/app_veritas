<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class AuthController extends Controller
{
    public function user() {
        $user = auth()->user();
        $user->load('user_profiles');
        return response()->json($user);
    }
}
