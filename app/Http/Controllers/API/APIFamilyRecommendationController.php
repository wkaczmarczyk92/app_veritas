<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\FamilyRecommendation;
use Exception;

class APIFamilyRecommendationController extends Controller
{
    public function update(Request $request) {
        if (!isset($request->id) or empty($request->id)) {
            return response()->json([
                'success' => false,
                'msg' => 'Not enough data.'
            ]);
        }

        try {
            $family_recommendation = FamilyRecommendation::where('veritas_id', $request->id)->first();
            $family_recommendation->bonus_payout_completed = true;
            $family_recommendation->save();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Wystąpił bład podczas połączenia.'
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Rekomendacje rodziny zostały zaktulizowane.'
        ]);
    }
}