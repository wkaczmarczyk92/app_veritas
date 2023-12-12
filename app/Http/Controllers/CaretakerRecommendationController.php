<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CaretakerRecommendation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;

use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;
use Exception;
use App\Helpers\CURLRequest;
// use 

class CaretakerRecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = CaretakerRecommendation::with(['user.user_profiles', 'admin_user.user_profiles']);

        if (isset($request->search) and $request->search != '') {
            $search = $request->search;
            $data->where('id', '=', $request->search)
                ->orWhere('caretaker_first_name', 'like', "%{$search}%")
                ->orWhere('caretaker_last_name', 'like', "%{$search}%")
                ->orWhere('caretaker_email', 'like', "%{$search}%")
                ->orWhere('caretaker_phone_number', 'like', "%{$search}%")
                ->orWhereHas('user.user_profiles', function($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(last_name, ' ', first_name) like ?", ["%{$search}%"]);
                });
        }

        $data = $data->orderBy('created_at', 'desc')->paginate(12);
            
        return Inertia::render('Admin/CaretakerRecommendations', [
            'data' => $data,
            'search' => $request->search ?? null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            CaretakerRecommendation::create([
                'user_id' => Auth::user()->id
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Admin/CaretakerRecommendation', [
            'data' => CaretakerRecommendation::with(['user.user_profiles', 'admin_user.user_profiles'])->find($id),
            'language' => Language::all() 
        ]);
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
            $validate = $request->validate([
                'caretaker_first_name' => 'required|string',
                'caretaker_last_name' => 'required|string',
                'caretaker_email' => 'sometimes|nullable|email',
                'caretaker_phone_number' => 'required|string',
                'language_id' => 'required|integer'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false, 
                'errors' => $e->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            $cr = CaretakerRecommendation::with(['user.user_profiles', 'admin_user.user_profiles'])->find($request->id);
            $cr->caretaker_first_name = $request->caretaker_first_name;
            $cr->caretaker_last_name = $request->caretaker_last_name;
            $cr->caretaker_email = $request->caretaker_email;
            $cr->caretaker_phone_number = $request->caretaker_phone_number;
            $cr->language_id = $request->language_id;
            $cr->locked = true;
            $cr->save();

            $curl_request = new CURLRequest;
            $arr = [
                'first_name' => $request->caretaker_first_name,
                'last_name' => $request->caretaker_last_name,
                'language' => Language::where('id', '=', $request->language_id)->pluck('name')[0],
                'phone_number' => $request->caretaker_phone_number
            ];
            
            $response = $curl_request->send_new_caretaker_recommendation_to_leads($arr);

            if (!$response->success) {
                throw new Exception('CURL Request failed.');
            }

            $cr->crm_lead_id = $response->result->lead_id;
            $cr->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'alert_type' => 'danger',
                'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.',
                // 'api_msg' => $response->msg ?? null,
                'exception' => $e->getMessage()
            ]);
        }
        
        return response()->json([
            'success' => true, 
            'alert_type' => 'success',
            'msg' => 'Dane opiekunki zostały zaktualizowane i przekazane do CC.',
            'data' => $cr,
            'response' => $response ?? null
        ]);
    }

    public function updateBonusPayout(Request $request)
    {
        $return_data = [];

        DB::beginTransaction();
        try {

            foreach ($request->items as $item) {
                $new_item = CaretakerRecommendation::with(['user.user_profiles', 'admin_user.user_profiles'])->find($item['id']);
                $new_item->bonus_payout_completed = true;
                $new_item->save();

                $return_data[] = $new_item;
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th->getMessage());
            return response()->json(['success' => false, 'msg' => 'Błąd podczas połączenia. Spróbuj ponownie później.']);
        }

        return response()->json(['success' => true, 'data' => $return_data]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
