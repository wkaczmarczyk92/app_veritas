<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\PointsSettingsPayout;
use App\Models\CashSettingsPayout;
use App\Models\Bonus;

use App\Helpers\Response;

class SettingsService
{
    public function get_points_to_payout() {
        return PointsSettingsPayout::where('id', '=', 1)
            ->pluck('points_to_payout')[0];
    }

    public function get_payout_cash() {
        return CashSettingsPayout::where('id', '=', 1)
            ->pluck('payout_cash')[0];
    }

    // metoda aktualizuje wartości bonusów za polecenie OPIEKUNKI i polecenie RODZIN
    public function update_bonuses($family_bonus, $caretaker_bonus) {
        DB::beginTransaction();

        try {
            $family_recommendation      = Bonus::where('name', '=', 'family_recommendation')->first();
            $caretaker_recommendation   = Bonus::where('name', '=', 'caretaker_recommendation')->first();

            $family_recommendation->bonus_value     = $family_bonus;
            $caretaker_recommendation->bonus_value  = $caretaker_bonus;

            $family_recommendation->save();
            $caretaker_recommendation->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Wystąpił błąd podczas połączenia. Spróbuj ponownie później.');
        }

        return Response::success('Dane zostały zaktualizowane.', [
            'bonus' => [
                'family_recommendation'     => $family_recommendation,
                'caretaker_recommendation'  =>  $caretaker_recommendation
            ]
        ]);
    }
}
