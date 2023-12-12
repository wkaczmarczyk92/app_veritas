<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;
use App\Http\Controllers\LevelBonusValueController;
use App\Http\Controllers\MultiplierController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\CleanDBController;

Route::middleware(['auth', 'role:super-admin'])->group(function() {

    Route::get('/command', [TestController::class, 'command'])->name('command');
    Route::post('command-call', [TestController::class, 'callCommand'])->name('command.call');

    Route::get('/clean.db', [CleanDBController::class, 'clean'])->name('clean.db');

    Route::get('/ustawienia-bonusow-za-wejscie-na-poziom', [LevelBonusValueController::class, 'index'])->name('level.bonus.value.index');
    Route::post('/level.bonus.value.store', [LevelBonusValueController::class, 'store'])->name('level.bonus.value.store');

    Route::get('/ustawienia-mnoznikow', [MultiplierController::class, 'index'])->name('multiplier_settings');
    Route::post('/multiplier.update', [MultiplierController::class, 'update'])->name('multiplier.update');

    Route::get('/ustawienia-punktow', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings.update', [SettingsController::class, 'update'])->name('settings.update');


    Route::get('/ustawienia-bonusow-za-polecenia', [BonusController::class, 'index'])->name('bonus.index');
    Route::patch('/bonus.update', [BonusController::class, 'update'])->name('bonus.update');

    Route::post('/down', [TestController::class, 'down'])->name('artisan.down');
    Route::post('/up', [TestController::class, 'up'])->name('artisan.up');

});