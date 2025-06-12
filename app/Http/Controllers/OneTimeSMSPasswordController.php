<?php

namespace App\Http\Controllers;

use App\Models\OneTimeSMSPassword;
use App\Http\Requests\StoreOneTimeSMSPasswordRequest;
use App\Http\Requests\UpdateOneTimeSMSPasswordRequest;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Support\Facades\Hash;

use App\Helpers\SMS;

use App\Helpers\Response;

class OneTimeSMSPasswordController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        try {
            $validate = $request->validate([
                'pesel' => 'sometimes|required|numeric|digits:11|exists:users,pesel',
                'sms_code' => 'sometimes|required|numeric|unique:one_time_s_m_s_passwords,password'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()]);
        }

        $user = User::where('pesel', $request->pesel)->first();

        if (OneTimeSMSPassword::where('user_id', $user->id)->whereDate('created_at', date('Y-m-d'))->exists()) {
            $sms_password = OneTimeSMSPassword::where('user_id', $user->id)->whereDate('created_at', date('Y-m-d'))->first();

            return Response::success('Sms został już dzisiaj do Ciebie wysłany.', [
                'code' => $sms_password->password,
                'replay' => true
            ]);
        }

        do {
            $password = random_int(100000, 999999);
        } while (OneTimeSMSPassword::where('password', $password)->first());

        $user->load('user_profiles');
        // $user = User::with('user_profiles')->where('pesel', $request->pesel)->first();

        DB::beginTransaction();

        try {
            OneTimeSMSPassword::create([
                'user_id' => $user->id,
                'password' => $password
            ]);

            $sms = new SMS;
            $sms->params['to'] = $user->user_profiles->phone_number;
            $sms->params['message'] = "Twój jednorazowy kod: {$password}";

            $result = $sms->send();

            if (!$result) {
                throw new Exception('SMS failed.');
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.', e: $e);
            // return response()->json([
            //     'success' => false,
            //     'msg' => 'Coś poszlo nie tak. Spróbuj ponownie później.',
            //     'exception' => $e->getMessage()
            // ]);
        }

        return Response::success('SMS został wysłany.', [
            'code' => $password
        ]);
        // return response()->json([
        //     'success' => true,
        //     'msg' => '',
        //     'code' => $password
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request);
        try {
            $validate = $request->validate([
                'pesel' => 'required|numeric|digits:11|exists:users,pesel',
                'code' => 'required|numeric|exists:one_time_s_m_s_passwords,password'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()]);
        }

        $user = User::where('pesel', $request->pesel)->first();

        $one_time_password = OneTimeSMSPassword::where('user_id', $user->id)
            ->where('password', $request->code)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($one_time_password->used) {
            return response()->json([
                'success' => false,
                'errors' => ['code' => 'Kod został już użyty.']
            ]);
        }

        $one_time_password->used = true;
        $one_time_password->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function new_password(Request $request)
    {
        try {
            $validate = $request->validate([
                'password' => 'required|string|confirmed',
                'password_confirmation' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()]);
        }

        $user = User::where('pesel', $request->pesel)
            ->first();

        $user->password = Hash::make($request->password);
        $user->save();

        OneTimeSMSPassword::where('user_id', $user->id)
            ->update(['used' => true]);

        return response()->json([
            'success' => true
        ]);
    }
}
