<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CRMCaretaker;
use App\Models\User;
use App\Models\UserProfile;

use Illuminate\Support\Facades\Hash;

use App\Exports\CaretakersExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CRMCaretakerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        set_time_limit(0);
        DB::connection('crm_database')->enableQueryLog();

        $caretakers = CRMCaretaker::on('crm_database')
            ->with(['crm_assignments', 'crm_assignments.crm_company'])
            ->whereHas('crm_assignments', function ($query) {
                $query->where('ssg_departure_date', '>=', '2023-01-01')
                    ->where('ssg_canceled', 0)
                    ->where('ssg_temporary_canceled', 0)
                    ->whereHas('crm_company', function ($query) {
                        $query->whereIn('cmp_id_company_group', [1, 2]);
                    });
            })
            ->whereNotNull('crt_last_name')
            ->whereNotNull('crt_first_name')
            ->whereNotNull('crt_pesel')
            ->whereRaw('crt_pesel REGEXP "^[0-9]+$"')
            ->whereRaw('LENGTH(crt_pesel) = 11')
            ->whereRaw('crt_pesel != 00000000000')
            ->whereNotNull('crt_main_phone_number')
            ->whereNotNull('crt_main_email')
            ->whereNotNull('crt_id_user_recruiter')
            ->get([
                'crt_last_name',
                'crt_first_name',
                'crt_pesel',
                'crt_main_phone_number',
                'crt_main_email',
                'crt_id_user_recruiter',
                'crt_id_caretaker'
            ]);

        $collection = [];

        foreach ($caretakers as $caretaker) {
            $user = User::firstOrNew([
                'pesel' => $caretaker->crt_pesel
            ]);

            $password = Str::random();

            $data = [
                'email' => null,
                'password' => Hash::make($password),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'plain_password' => $password,
                'pesel' => $caretaker->crt_pesel
            ];

            $user->fill($data);

            $collection[] = $data;

            if (!$user->exists) {
                $user->save();
                $user->assignRole('user');
                $user_profile = new UserProfile([
                    'first_name' => $caretaker->crt_first_name,
                    'last_name' => $caretaker->crt_last_name,
                    'level' => 1,
                    'phone_number' => $caretaker->crt_main_phone_number,
                    'crt_id_caretaker' => $caretaker->crt_id_caretaker,
                    'email' => $caretaker->crt_main_email,
                    'crt_id_user_recruiter' => $caretaker->crt_id_user_recruiter,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $user->user_profiles()->save($user_profile);
            }
        }

        return Excel::download(new CaretakersExport($collection), 'users.xlsx');
    }

    public function updateCaretakerId()
    {
        set_time_limit(0);

        $users = User::with('user_profiles')
            ->whereNotNull('pesel')
            ->get();

        foreach ($users as $user) {
            $caretaker_id = CRMCaretaker::on('crm_database')->where('crt_pesel', '=', $user->pesel)->pluck('crt_id_caretaker')->first();
            $user->user_profiles->crt_id_caretaker = $caretaker_id;
            $user->user_profiles->save();
        }
    }

    public function downloadSingleCaretaker()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
