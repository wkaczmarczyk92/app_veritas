<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Testing\ExcelTestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomePageController;

use App\Http\Controllers\CRMOfferDownloadController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\FreeAccountUser\PremiumAccountController;
use App\Http\Controllers\TestController;

Route::middleware(['auth', 'role:admin|super-admin'])->group(function () {
    Route::get('/testowanie/excel-export', [ExcelTestController::class, 'export'])->name('text.excel.index');

    Route::controller(TestController::class)->prefix('testy')->name('test')->group(function () {
        Route::get('/usuwanie-pliku', 'remove_file')->name('remove.file');
    });
});

Route::controller(RegisterController::class)->prefix('rejestracja')->name('register.')->group(function () {
    Route::get('/', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/weryfikacja-sms/{register_url_token}/{p}', 'verification')->name('verification');
    Route::patch('/weryfikacja-sms/{p}', 'verify')->name('verify');
});

Route::prefix('konto-darmowe')->group(function () {
    Route::get('/', [HomePageController::class, 'index_free_account'])->name('home.index_free_account');

    Route::controller(CRMOfferDownloadController::class)->prefix('oferty')->name('offer.')->group(function () {
        Route::post('/pobierz', 'download_no_crm')->name('download.no.crm');
    });

    Route::controller(OfferController::class)->prefix('oferty')->name('offer.')->group(function () {
        Route::get('/', 'index_free_account')->name('index_free_account');
        Route::get('/moje-zgloszenia', 'my_offers_free_account')->name('my_offers_free_account');
    });

    Route::controller(PremiumAccountController::class)->prefix('zdobadz-konto-premium')->name('premium.')->group(function () {
        Route::get('/', 'index')->name('index');
    });
});


Route::get('/moje-zgloszenia', [OfferController::class, 'my_offers_free_account'])->name('my_offers_free_account');
