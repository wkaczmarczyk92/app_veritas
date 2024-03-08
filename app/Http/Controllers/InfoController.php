<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\CRMRecruiter;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $user->load('user_profiles');

        $recruiter = CRMRecruiter::on('crm_database')
            ->where('usr_id_user', '=', $user->user_profiles->crt_id_user_recruiter)
            ->first();

        return Inertia::render('User/Info', [
            'recruiter' => $recruiter,
            'user' => $user
        ]);
    }
}
