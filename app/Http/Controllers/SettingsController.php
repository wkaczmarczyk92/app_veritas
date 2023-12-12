<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;

use App\Models\PointsSettingsPayout;
use App\Models\CashSettingsPayout;

use Illuminate\Support\Facades\DB;

use Exception;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = [
            'payout_cash' => CashSettingsPayout::where('id', '=', 1)->pluck('payout_cash')[0],
            'points_to_payout' => PointsSettingsPayout::where('id', '=', 1)->pluck('points_to_payout')[0]
        ];

        return Inertia::render('Admin/PointsSettings', [
            'settings' => $settings
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {   
        $validation = $request->validate([
            'payout_cash' => 'required|integer|min:1',
            'points_to_payout' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $cash_settings_payout = CashSettingsPayout::find(1);
            $cash_settings_payout->payout_cash = $request->payout_cash;
            $cash_settings_payout->save();

            $points_settings_payout = PointsSettingsPayout::find(1);
            $points_settings_payout->points_to_payout = $request->points_to_payout;
            $points_settings_payout->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->withErrors(['error' => $th->getMessage()]);
        }

        return back()->with('success_msg', 'Dane zostały zaktualizowane.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
