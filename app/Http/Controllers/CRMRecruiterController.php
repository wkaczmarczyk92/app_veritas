<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CRMRecruiter;
use App\Models\UserProfile;

use App\Http\Requests\CRM\Recruiter\SearchFormRequest;

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

    public function search_in_crm(SearchFormRequest $request) {
        if ($request->email) {
            $recruiter = CRMRecruiter::on('crm_database')
                ->where('usr_email', 'like', "%{$request->email}%")
                ->first();
        } else {
            $recruiter = CRMRecruiter::on('crm_database')
                ->where('usr_id_user', '=', $request->crm_id)
                ->where('usr_email', '!=', null)
                ->first();
        }

        // $recruiter = CRMRecruiter::on('crm_database')
        //     ->where(function($query) use ($request){
        //         $query->where('usr_id_user', '=', $request->crm_id)
        //             ->where('usr_id_user', '!=', null);
        //     })
        //     ->orWhere(function($query) use ($request) {
        //         $query->where('usr_email', 'like', "%{$request->email}%")
        //             ->where('usr_email', '!=', null);
        //     })
        //     ->first();

        return response()->json([
            'recruiter' => $recruiter
        ]);
    }
    // public function email()
}
