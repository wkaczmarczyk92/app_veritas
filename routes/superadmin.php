<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;
use App\Http\Controllers\LevelBonusValueController;
use App\Http\Controllers\MultiplierController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\CleanDBController;

Route::middleware(['auth', 'role:super-admin'])->group(function () {

    Route::controller(TestController::class)->group(function () {
        Route::get('/command', 'command')->name('command');
        Route::post('command-call', 'callCommand')->name('command.call');
    });

    Route::get('/clean.db', [CleanDBController::class, 'clean'])->name('clean.db');

    Route::controller(LevelBonusValueController::class)->group(function () {
        Route::name('level.')->group(function () {
            Route::get('/ustawienia-bonusow-za-wejscie-na-poziom', 'index')->name('bonus.value.index');
            Route::post('/level.bonus.value.store', 'store')->name('bonus.value.store');
        });
    });

    Route::controller(MultiplierController::class)->group(function () {
        Route::get('/ustawienia-mnoznikow', 'index')->name('multiplier_settings');
        Route::post('/multiplier.update', 'update')->name('multiplier.update');
    });

    Route::controller(SettingsController::class)->group(function () {
        Route::name('settings.')->group(function () {
            Route::get('/ustawienia-punktow', 'index')->name('index');
            Route::post('/settings.update', 'update')->name('update');
        });
    });

    Route::controller(BonusController::class)->group(function () {
        Route::name('bonus.')->group(function () {
            Route::get('/ustawienia-bonusow-za-polecenia', 'index')->name('index');
            Route::patch('/bonus.update', 'update')->name('update');
        });
    });

    Route::controller(TestController::class)->group(function () {
        Route::name('artisan.')->group(function () {
            Route::post('/down', 'down')->name('down');
            Route::post('/up', 'up')->name('up');
        });

        Route::get('/email_test', [TestController::class, 'email_offer'])->name('email_test');
    });

    // Route::post('/down', [TestController::class, 'down'])->name('artisan.down');
    // Route::post('/up', [TestController::class, 'up'])->name('artisan.up');

    // Route::get('/mmm', [TestController::class, 'manual_update'])->name('mmm');

    // Route::get('/email_test', [TestController::class, 'email_offer'])->name('email_test');
});
