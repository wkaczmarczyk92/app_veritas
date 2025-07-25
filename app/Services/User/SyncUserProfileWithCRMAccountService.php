<?php

namespace App\Services\User;

use App\Models\UserProfile;
use App\Models\User;
use App\Models\CRMCaretaker;
use Exception;
use App\Helpers\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\CRMRecruiter;
use App\Helpers\Transaction;

class SyncUserProfileWithCRMAccountService 
{
    public function __invoke(int $user_id) {
        try {
            $user = User::with('user_profiles')->findOrFail($user_id);
            $crm_caretaker = CRMCaretaker::where('crt_pesel', $user->pesel)->firstOrFail();


            $user->user_profiles->crt_id_caretaker = $crm_caretaker->crt_id_caretaker;
            $user->user_profiles->crt_id_user_recruiter = $crm_caretaker->crt_id_user_recruiter;

            $crm_recruiter = CRMRecruiter::where('usr_id_user', $user->user_profiles->crt_id_user_recruiter)->firstOrFail();
            
            $user->user_profiles->recruiter_first_name = $crm_recruiter->usr_first_name;
            $user->user_profiles->recruiter_last_name = $crm_recruiter->usr_last_name;
            
            DB::transaction(function () use ($user) {
                $user->user_profiles->save();
            });

        } catch(ModelNotFoundException $e) {
            return Response::danger('Użytkownik nie został znaleziony. Odśwież stronę i spróbuj ponownie.', e: $e);
        } catch (Exception $e) {
            return Response::danger($e->getMessage());
        }

        return Response::success('Użytkownik został sparowany z CRM-em', [
            'user' => $user
        ]);
    }
}