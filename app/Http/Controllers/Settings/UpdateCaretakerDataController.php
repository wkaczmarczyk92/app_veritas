<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Transaction;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Helpers\Response;
use App\Models\CRMRecruiter;
use App\Models\CRMCaretaker;

class UpdateCaretakerDataController extends Controller
{
    public function update_caretakers_recruiter(Request $request)
    {
        set_time_limit(0);

        $caretakers = [];

        User::with('user_profiles')
            ->role('user')
            ->chunk(100, function ($users) use (&$caretakers) {
                // Zbierz wszystkie potrzebne ID do jednego zapytania
                $caretakerIds = $users->pluck('user_profiles.crt_id_caretaker')->filter()->unique()->toArray();

                // Pobierz hurtowo opiekunów
                $crm_caretakers = CRMCaretaker::whereIn('crt_id_caretaker', $caretakerIds)->get()->keyBy('crt_id_caretaker');

                // Zbierz ID rekruterów
                $recruiterIds = $crm_caretakers->pluck('crt_id_user_recruiter')->filter()->unique()->toArray();

                // Pobierz hurtowo rekruterów
                $crm_recruiters = CRMRecruiter::whereIn('usr_id_user', $recruiterIds)->get()->keyBy('usr_id_user');

                DB::beginTransaction();

                try {
                    foreach ($users as $user) {
                        $profile = $user->user_profiles;

                        if (!$profile || !$profile->crt_id_caretaker) {
                            $caretakers[] = [
                                'user_id' => $user->id,
                                'brak danych'
                            ];
                            continue;
                        }

                        $crm_caretaker = $crm_caretakers[$profile->crt_id_caretaker] ?? null;

                        if (!$crm_caretaker || !$crm_caretaker->crt_id_user_recruiter) {
                            continue;
                        }

                        $crm_recruiter = $crm_recruiters[$crm_caretaker->crt_id_user_recruiter] ?? null;

                        if (!$crm_recruiter) {
                            $caretakers[] = [
                                'user_id' => $user->id,
                                'brak rekrutera'
                            ];
                            continue;
                        }

                        $caretakers[] = [
                            'user_id' => $user->id,
                            'recruiter_first_name'   => $crm_recruiter->usr_first_name,
                            'recruiter_last_name'    => $crm_recruiter->usr_last_name,
                            'crt_id_user_recruiter'  => $crm_recruiter->usr_id_user,
                        ];

                        // Wykonaj bezpieczną aktualizację
                        $profile->update([
                            'recruiter_first_name'   => $crm_recruiter->usr_first_name,
                            'recruiter_last_name'    => $crm_recruiter->usr_last_name,
                            'crt_id_user_recruiter'  => $crm_recruiter->usr_id_user,
                        ]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return Response::danger('oś poszło nie tak.', e: $e);
                }
            });

        return Response::success('Rekruterzy przypisani do opiekunek zostali zaktualizowani.', [
            'caretakers' => $caretakers
        ]);
    }
}
