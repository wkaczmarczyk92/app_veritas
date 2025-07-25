<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Common\HashController;

use App\Http\Controllers\Common\MediaLibraryController;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('guest')->group(function () {

    Route::controller(RegisteredUserController::class)->prefix('rejestracja')->name('register.')->group(function () {
        Route::get('/', 'create')->name('create');
        Route::post('/', 'store')->name('store');
    });

    Route::controller(AuthenticatedSessionController::class)->prefix('zaloguj-sie')->group(function () {
        Route::get('/', 'create')->name('login');
        Route::post('/', 'store');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::post('uzytkownik', 'user')->name('user');
    });

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::controller(ConfirmablePasswordController::class)->prefix('potwierdz-haslo')->group(function () {
        Route::get('/', 'show')->name('password.confirm');
        Route::post('/', 'store');
    });

    Route::controller(PasswordController::class)
        ->prefix('haslo')
        ->name('password.')
        ->group(function () {
            Route::put('/', 'update')->name('update');
            Route::get('generuj', 'generate')->name('generate');
        });


    Route::post('wyloguj-sie', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');


    Route::controller(MediaLibraryController::class)
        ->prefix('biblioteka-mediow')
        ->name('media.library.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('dodaj', 'store')->name('store');
            Route::get('pobierz', 'get')->name('get');

            Route::patch('aktualizuj', 'update')->name('update');
            Route::post('generuj-hash', 'generate_hash')->name('hash.generate');
        });

    Route::post('/hash/pobierz', [HashController::class, 'get'])->name('hash.get');
});
