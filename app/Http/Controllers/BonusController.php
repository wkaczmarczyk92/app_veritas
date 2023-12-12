<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Bonus;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class BonusController extends Controller
{
    public function index() {
        return Inertia::render('Admin/BonusSettings', [
            'bonus' => [
                'family_recommendation' => Bonus::where('name', '=', 'family_recommendation')->first(),
                'caretaker_recommendation' =>  Bonus::where('name', '=', 'caretaker_recommendation')->first()
            ]
        ]);
    }

    public function update(Request $request) {
        try {
            $validate = $request->validate([
                'caretaker_bonus' => 'required|integer|min:1', 
                'family_bonus' => 'required|integer|min:1', 
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            DB::table('bonuses')
                ->where('name', 'family_recommendation')
                ->update(['bonus_value' => $request->family_bonus]);

            DB::table('bonuses')
                ->where('name', 'caretaker_recommendation')
                ->update(['bonus_value' => $request->caretaker_bonus]);

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
            'msg' => 'Dane zostały zaktualizowane.',
            'bonus' => [
                'family_recommendation' => Bonus::where('name', '=', 'family_recommendation')->first(),
                'caretaker_recommendation' =>  Bonus::where('name', '=', 'caretaker_recommendation')->first()
            ]
        ]);
    }
}
