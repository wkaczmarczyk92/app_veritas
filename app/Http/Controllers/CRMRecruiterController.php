<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CRMRecruiter;
use App\Models\UserProfile;

class CRMRecruiterController extends Controller
{
    public function index() {
        set_time_limit(0);

        $recruiters = CRMRecruiter::on('crm_database')
            ->get([
                'usr_id_user',
                'usr_first_name',
                'usr_last_name'
            ]);

        foreach ($recruiters as $recruiter) {
            UserProfile::where('crt_id_user_recruiter', $recruiter->usr_id_user)
                ->update([
                    'recruiter_first_name' => $recruiter->usr_first_name,
                    'recruiter_last_name' => $recruiter->usr_last_name
                ]);

        }
    }

    public function show($id) {
        $recruiter = CRMRecruiter::on('crm_database')
            ->where('usr_id_user', '=', $id)
            ->first();

        return response()->json($recruiter);
    }

    // public function email() 
}
