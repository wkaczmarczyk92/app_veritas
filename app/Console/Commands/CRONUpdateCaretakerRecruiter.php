<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\CRMCaretaker;
use App\Models\CRMRecruiter;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class CRONUpdateCaretakerRecruiter extends Command
{
    protected $signature = 'cron:update-caretaker-recruiter';

    public function handle() {
        set_time_limit(0);

        $users = User::with('user_profiles')
            ->role('user')
            ->get();

        DB::beginTransaction();

        try {
            foreach ($users as $index => $user) {
                if ($user->user_profiles->crt_id_caretaker == null) {
                    continue;
                }

                $crm_caretaker = CRMCaretaker::where('crt_id_caretaker', '=', $user->user_profiles->crt_id_caretaker)->first();

                if ($crm_caretaker == null || $crm_caretaker->crt_id_user_recruiter == null) {
                    continue;
                }

                $crm_recruiter = CRMRecruiter::find($crm_caretaker->crt_id_user_recruiter);

                $user->user_profiles->recruiter_first_name = $crm_recruiter->usr_first_name;
                $user->user_profiles->recruiter_last_name = $crm_recruiter->usr_last_name;
                $user->user_profiles->crt_id_user_recruiter = $crm_recruiter->usr_id_user;

                $user->user_profiles->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            echo json_encode([
                'success' => false,
                'msg' => '',
                'exception' => $e->getMessage()
            ]);
            die();
        }        

        echo json_encode([
            'success' => true,
            'msg' => 'Udało się.',
            'exception' => ''
        ]);
    }
}