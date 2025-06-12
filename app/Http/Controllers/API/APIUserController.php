<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserProfile;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use App\Models\PasswordForUser;

use Illuminate\Support\Facades\Auth;

use App\Services\CRM\ProfileImageService;

class APIUserController extends Controller
{
    // {
    //     "pesel": 83031420610,
    //     "crt_id_caretaker": 31471,
    //     "user_profiles": {
    //         "first_name": "Basia",
    //         "last_name": "Testowa",
    //         "email": "b.testowa@gmail.com",
    //         "phone_number": "12331232312"
    //     }
    // }



    public function update(Request $request)
    {
        $crt_id_caretaker = $request->carerId;

        $user = User::with('user_profiles')->whereHas('user_profiles', function ($query) use ($crt_id_caretaker) {
            $query->where('crt_id_caretaker', '=', $crt_id_caretaker);
        })->first();

        if ($user == null) {
            echo json_encode([
                'success' => true,
                'msg' => 'Nie znaleziono użytkownika.',
                'errors' => ''
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $user->pesel = $request->data['crt_pesel'] ?? $user->pesel;
        $user->user_profiles->first_name = $request->data['crt_first_name'] ?? $user->user_profiles->first_name;
        $user->user_profiles->last_name = $request->data['crt_last_name'] ?? $user->user_profiles->last_name;
        $user->user_profiles->email = $request->data['crt_main_email'] ?? $user->user_profiles->email;
        $user->user_profiles->phone_number = $request->data['crt_main_phone_number'] ?? $user->user_profiles->phone_number;
        $user->user_profiles->recruiter_first_name = $request->data['usr_first_name'] ?? $user->user_profiles->recruiter_first_name;
        $user->user_profiles->recruiter_last_name = $request->data['usr_last_name'] ?? $user->user_profiles->recruiter_last_name;
        $user->user_profiles->crt_id_user_recruiter = $request->data['usr_id_user'] ?? $user->user_profiles->crt_id_user_recruiter;

        DB::beginTransaction();

        try {
            $user->save();
            $user->user_profiles->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            echo json_encode([
                'success' => false,
                'msg' => 'Błąd podczas aktualizacji danych w systemi lojalnościowym.',
                'errors' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode([
            'success' => true,
            'msg' => 'Dane zostały pomyślnie zapisane w systemie lojalnościowym.',
            'errors' => ''
        ], JSON_UNESCAPED_UNICODE);
        return;
    }


    // {
    //     "pesel": 12312312322,
    //     "ssg_arrival_date": "2023-09-24",
    //     "user_profiles": {
    //         "first_name": "Marian",
    //         "last_name": "Paździoch",
    //         "email": "m.pazdzioch@gmail.com", // null
    //         "phone_number": "123123123", //null
    //         "crt_id_caretaker": 31471,
    //         "recruiter_first_name": null, //null
    //         "recruiter_last_name": null, //null
    //         "crt_id_user_recruiter": null //null
    //     }
    // }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'pesel' => 'required|string',
                'ssg_arrival_date' => 'required|string|max:10',
                'user_profiles.first_name' => 'required|string',
                'user_profiles.last_name' => 'required|string',
                'user_profiles.email' => 'sometimes|nullable|email',
                'user_profiles.phone_number' => 'sometimes|nullable|string',
                'user_profiles.crt_id_caretaker' => 'required|numeric',
                'user_profiles.crt_id_user_recruiter' => 'sometimes|nullable|numeric',
                'user_profiles.recruiter_first_name' => 'sometimes|nullable|string',
                'user_profiles.recruiter_last_name' => 'sometimes|nullable|string'

            ]);
        } catch (ValidationException $e) {
            echo json_encode(['success' => false, 'msg' => $e->errors()], JSON_UNESCAPED_UNICODE);
            return;
        }

        $user = User::with('user_profiles')->where('pesel', '=', $request->pesel)->first();
        if (!$user) {
            DB::beginTransaction();
            $password = Str::random();

            try {
                $user = User::create([
                    'pesel' => $request->pesel
                ]);

                $user->assignRole('user');

                $user_profile = new UserProfile([
                    'first_name' => $request->user_profiles['first_name'],
                    'last_name' => $request->user_profiles['last_name'],
                    'level' => 1,
                    'phone_number' => $request->user_profiles['phone_number'],
                    'crt_id_caretaker' => $request->user_profiles['crt_id_caretaker'],
                    'email' => $request->user_profiles['email'],
                    'crt_id_user_recruiter' => $request->user_profiles['crt_id_user_recruiter'],
                    'recruiter_first_name' => $request->user_profiles['recruiter_first_name'],
                    'recruiter_last_name' => $request->user_profiles['recruiter_last_name']
                ]);

                $password_for_user = new PasswordForUser([
                    'departure_date' => $request['ssg_arrival_date'],
                    'sent' => false
                ]);

                // $user_profile_image_service = new ProfileImageService;
                // $user_profile_image = $user_profile_image_service->download($user->id);

                $user->user_profiles()->save($user_profile);
                $user->password_for_user()->save($password_for_user);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                echo json_encode(['success' => false, 'msg' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
                return;
            }
        } else {
            $user->user_profiles->first_name = $request->user_profiles['first_name'];
            $user->user_profiles->last_name = $request->user_profiles['last_name'];
            $user->user_profiles->phone_number = $request->user_profiles['phone_number'];
            $user->user_profiles->email = $request->user_profiles['email'];
            $user->user_profiles->crt_id_user_recruiter = $request->user_profiles['crt_id_user_recruiter'];
            $user->user_profiles->recruiter_first_name = $request->user_profiles['recruiter_first_name'];
            $user->user_profiles->recruiter_last_name = $request->user_profiles['recruiter_last_name'];

            DB::beginTransaction();

            try {
                $user->user_profiles->save();
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                echo json_encode([
                    'success' => false,
                    'msg' => 'Wystąpił błąd podczas połączenia z bazą.',
                    'errors' => $e->getMessage()
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'msg' => 'Użytkownik znaleziony i zaktualizownay.'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'msg' => 'Użytkownik utworzony.'
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
}
