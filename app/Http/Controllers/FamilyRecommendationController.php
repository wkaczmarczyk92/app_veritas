<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;
use App\Models\FamilyRecommendation;

use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Helpers\CURLRequest;
use App\Models\User;

use App\Helpers\CountryCodeFinder;

class FamilyRecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/FamilyRecommendations', [
            'data' => FamilyRecommendation::with('user.user_profiles')
                ->orderBy('created_at', 'desc')
                ->paginate(50)
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
        try {
            $validate = $request->validate([
                'family_last_name' => 'required|string',
                'phone_number' => 'required|numeric',
                'info' => 'nullable|string',
                'processing_personal_data' => 'required|accepted|boolean'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['succes' => false, 'errors' => $e->validator->errors()]);
        }

        DB::beginTransaction();
        $user = User::with('user_profiles')->find(Auth::user()->id);

        try {
            $family_recommendation = FamilyRecommendation::create([
                'user_id' => $user->id,
                'family_last_name' => $request->family_last_name,
                'country_code' => $request->country_code,
                'phone_number' => $request->phone_number,
                'info' => $request->info,
                'processing_personal_data' => $request->processing_personal_data
            ]);

            $ccfinder = new CountryCodeFinder($user->user_profiles->phone_number);

            $curl_request = new CURLRequest;
            $curl_data = [
                'r_name' => $user->user_profiles['first_name'] . ' ' . $user->user_profiles['last_name'],
                'r_phone_number' => $ccfinder->phone(),
                'r_country_code' => $ccfinder->code(),
                'r_email' => $user->user_profiles['email'],
                'family_name' => $request->family_last_name,
                'family_phone_number' => $request->phone_number,
                'family_country_code' => $request->country_code,
                'api_recommendation_id' => $family_recommendation->id,
                'info' => $request->info
            ];

            $response = $curl_request->send_new_family_recommendation($curl_data);

            if (!$response->success) {
                throw new Exception('Błąd podczas połączenia. Spróbuj ponownie później.');
            }

            $family_recommendation->veritas_id = $response->veritas_id;
            $family_recommendation->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['succes' => false, 'msg' => $e->getMessage()]);
        }

        return response()->json(['success' => true]);
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
    public function destroy(Request $request)
    {
        // dd($request);
        try {
            $family_recommendation = FamilyRecommendation::find($request->api_recommendation_id);
            $family_recommendation->family_last_name = null;
            $family_recommendation->country_code = null;
            $family_recommendation->phone_number = null;
            $family_recommendation->info = null;
            $family_recommendation->rejected_text = $request->rejected_text;
            $family_recommendation->rejected = true;

            DB::beginTransaction();

            $family_recommendation->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => 'Wystąpił błąd podczas połączenia z bazą danych.',
                'exception' => $e->getMessage() ?? '-'
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Dane rodziny zostały usunięte.'
        ]);
    }
}
