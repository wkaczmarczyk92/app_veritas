<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Requests\Settings\BonusRequest;

use App\Models\Bonus;

use App\Services\SettingsService;

class BonusController extends Controller
{
    protected SettingsService $settings_service;

    public function __construct(SettingsService $settings_service) {
        $this->settings_service = $settings_service;
    }

    public function index() {
        return Inertia::render('Admin/BonusSettings', [
            'bonus' => [
                'family_recommendation'     => Bonus::where('name', '=', 'family_recommendation')->first(),
                'caretaker_recommendation'  =>  Bonus::where('name', '=', 'caretaker_recommendation')->first()
            ]
        ]);
    }

    public function update(BonusRequest $request) {
        return response()->json($this->settings_service->update_bonuses($request->family_bonus, $request->caretaker_bonus));
    }
}
