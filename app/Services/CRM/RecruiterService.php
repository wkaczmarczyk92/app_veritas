<?php

namespace App\Services\CRM;

use App\Models\CRMRecruiter;

class RecruiterService
{
    public function get($id) {
        $recruiter = CRMRecruiter::on('crm_database')
        ->where('usr_id_user', '=', $id)
        ->first();

        return $recruiter;
    }
}
