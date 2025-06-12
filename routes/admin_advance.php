<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SyncUserLevelController;
use App\Http\Controllers\Admin\CheckPointsController;
use App\Http\Controllers\Admin\ManualPayoutRequestController;


Route::middleware(['role:super-admin|admin'])->name('advance.settings.')->group(function () {
    Route::prefix('ustawienia-aplikacji/zaawansowane')->group(function () {
        Route::controller(SyncUserLevelController::class)->prefix('synchronizacja-poziomu')->name('sync.level.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'update')->name('update');
        });

        Route::controller(CheckPointsController::class)->prefix('punkty')->name('points.check.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'check')->name('check');
            Route::post('/dodaj-punkty', 'update')->name('update');
        });

        Route::controller(ManualPayoutRequestController::class)->prefix('generowanie-pliku-z-wnioskami-o-wyplaty')->name('manual.payout.request.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'update')->name('update');
        });
    });
});
