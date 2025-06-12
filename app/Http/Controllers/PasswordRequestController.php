<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

use Inertia\Inertia;

class PasswordRequestController extends Controller
{
    public function count()
    {
        return response()->json([
            'password_request_count' => PasswordRequest::where('active', true)->get()->count()
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $password_requests = PasswordRequest::with('user.user_profiles')->where('active', true)->latest()->get();

        return Inertia::render('Admin/PasswordRequests', [
            'password_requests' => $password_requests
        ]);
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
        // dd($request->all());
        try {
            $valiate = $request->validate([
                'pesel' => 'required|numeric'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->errors()
            ]);
        }

        $user = User::where('pesel', $request->pesel)->first();

        if ($user) {
            DB::beginTransaction();
            try {
                PasswordRequest::create([
                    'user_id' => $user->id
                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'alert_type' => 'danger',
                    'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'alert_type' => 'success',
            'msg' => 'Jeśli wpisałeś prawidłowy PESEL nasz konsultant skontaktuje się z Tobą w sprawie zmiany hasła.'
        ]);
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
    public function update(Request $request)
    {
        try {
            // $validate = $request->validate([
            //     'user_id' => 'required|int',
            //     'password' => ['required', 'confirmed', Password::defaults()]
            // ]);
            $validate = $request->validate([
                'user_id' => 'required|int',
                'password' => ['required', 'confirmed']
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            $user = User::find($request->user_id);
            $user->password = Hash::make($request->password);
            $user->save();

            PasswordRequest::where('user_id', $request->user_id)->update([
                'active' => false
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'alert_type' => 'danger',
                'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.'
            ]);
        }

        return response()->json([
            'success' => true,
            'alert_type' => 'success',
            'msg' => 'Hasło użytkownika zostało zaktualizowane.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
