<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserPointController;

use App\Http\Controllers\Common\HashController;

use App\Http\Controllers\Common\MediaLibraryController;
use App\Http\Controllers\Test\GermanTestController;

Route::middleware('guest')->group(function () {





    Route::controller(RegisteredUserController::class)->prefix('rejestracja')->name('register.')->group(function () {
        Route::get('/', 'create')->name('create');
        Route::post('/', 'store')->name('store');
    });




    Route::get('zaloguj-sie', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('zaloguj-sie', [AuthenticatedSessionController::class, 'store']);

    // Route::get('forgot-password', [PasswordResetLinkController::classdas, 'create'])
    //             ->name('password.request');

    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    //             ->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    //             ->name('password.reset');

    // Route::post('reset-password', [NewPasswordController::class, 'store'])
    //             ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/password-generate', [PasswordController::class, 'generate'])->name('password.generate');


    Route::get('/biblioteka-mediow', [MediaLibraryController::class, 'index'])->name('media.library.index');
    Route::post('/biblioteka-mediow/dodaj', [MediaLibraryController::class, 'store'])->name('media.library.store');
    Route::get('/biblioteka-mediow/pobierz', [MediaLibraryController::class, 'get'])->name('media.library.get');

    Route::patch('/biblioteka-mediow/aktualizuj', [MediaLibraryController::class, 'update'])->name('media.library.update');
    Route::post('/biblioteka-mediow/generuj-hash', [MediaLibraryController::class, 'generate_hash'])->name('media.library.hash.generate');

    Route::post('/hash/pobierz', [HashController::class, 'get'])->name('hash.get');
});
