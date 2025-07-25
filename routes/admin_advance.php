<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SyncUserLevelController;
use App\Http\Controllers\Admin\CheckPointsController;
use App\Http\Controllers\Admin\ManualPayoutRequestController;
use App\Http\Controllers\UserController;

Route::middleware(['auth', 'role:god_mode'])->group(function () {

    Route::name('app.settings.')->group(function () {
        Route::prefix('ustawienia-aplikacji/zaawansowane')->group(function () {

            Route::controller(SyncUserLevelController::class)
                ->prefix('synchronizacja-poziomu')
                ->name('sync.level.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'update')->name('update');
                });

            Route::controller(ManualPayoutRequestController::class)
                ->prefix('generowanie-pliku-z-wnioskami-o-wyplaty')
                ->name('manual.payout.request.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'update')->name('update');
                });
        });
    });

    Route::controller(UserController::class)->prefix('uzytkownicy')->name('users.')->group(function () {
        Route::get('stworz-uzytkownika', 'create')->name('create');
        Route::post('stworz-uzytkownika', 'store')->name('store');
    });
});

Route::middleware(['auth', 'role:admin|super-admin'])->group(function () {
    Route::name('app.settings.')->group(function () {
        Route::prefix('ustawienia-aplikacji/zaawansowane')->group(function () {
            Route::controller(CheckPointsController::class)
                ->prefix('punkty')
                ->name('points.check.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'check')->name('check');
                    Route::post('/dodaj-punkty', 'update')->name('update');
                });
        });
    });
});
