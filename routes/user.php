<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\UserProfile;
use App\Models\CashSettingsPayout;
use App\Models\PointsSettingsPayout;

use App\Http\Controllers\BOKSubjectController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\ReadyToDepartureDateController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\FamilyRecommendationController;
use App\Http\Controllers\PayoutRequestController;
use App\Http\Controllers\BOKRequestController;
use App\Http\Controllers\CRMOfferDownloadController;
use App\Http\Controllers\CaretakerRecommendationController;

use App\Http\Controllers\UserProfileController;

use App\Http\Controllers\OfferController;
use App\Models\FamilyRecommendation;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\InfoController;

use App\Http\Controllers\DownloadFileController;

use App\Http\Controllers\Auth\PasswordController;

use App\Http\Controllers\CRM\CRMDocumentController;

use App\Models\Banking\AccountType;

use App\Http\Controllers\Common\CookieController;
use App\Http\Controllers\Test\GermanTestController;
use App\Http\Controllers\Lessons\GermanLessonController;

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::controller(GermanLessonController::class)
        ->middleware(['seen_test_regulations', 'is_test_user'])
        ->prefix('lekcje-niemieckiego')
        ->name('user.german.lessons.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/lekcja/{id}', 'show')->name('show');
        });

    Route::get('zasady-programy-zostan-mittelem', [GermanTestController::class, 'become_mittel_program'])->prefix('lekcje-niemieckiego')->name('user.german.lessons.become.mittel.program');


    // Route::controller(GermanTestController::class)->middleware(['seen_test_regulations', 'is_test_user'])->prefix('test-niemieckiego')->name('user.german.test.')->group(function () {
    Route::controller(GermanTestController::class)->middleware(['seen_test_regulations'])->prefix('test-niemieckiego')->name('user.german.test.')->group(function () {
        Route::get('/', 'show')->name('show');
    });

    Route::controller(BOKSubjectController::class)->group(function () {
        Route::get('/bok-subjects', 'index')->name('boksubject.index');
    });

    Route::controller(BOKRequestController::class)->group(function () {
        Route::post('/bok-request', 'store')->name('bokrequest.store');
    });

    Route::get('/', [HomePageController::class, 'index'])->name('/');

    Route::get('/pliki-do-pobrania', [DownloadFileController::class, 'files_to_download'])->name('download.files');

    Route::get('/polec-opiekunke', [CaretakerRecommendationController::class, 'create'])->name('caretakerrecommendation.create');

    Route::controller(FamilyRecommendationController::class)->group(function () {
        Route::name('familyrecommendation.')->group(function () {
            Route::get('/polec-rodzine', 'create')->name('create');
            Route::post('/familyrecommendation.store', 'store')->name('store');
        });
    });

    Route::controller(PasswordController::class)->group(function () {
        Route::name('password.')->group(function () {
            Route::get('/zmien-haslo', 'edit')->name('edit');
            Route::post('/zmien-haslo', 'update')->name('update');
        });
    });

    Route::controller(ReadyToDepartureDateController::class)->group(function () {
        Route::name('readytodeparturedate.')->group(function () {
            Route::post('/readytodeparturedate.store.or.update', 'storeOrUpdate')->name('store.or.update');
            Route::patch('/readytodeparturedate.destroy', 'destroy')->name('destroy');
        });
    });

    Route::controller(OfferController::class)->group(function () {
        Route::name('offer.')->group(function () {
            Route::get('/szukaj-ofert', 'user_offers')->name('user');
            Route::post('/offer.store', 'store')->name('store');
        });
    });

    Route::get('/moje-konto', [UserProfileController::class, 'show'])->name('userprofile.show');

    Route::get('/kursy', CourseController::class)->name('courses');
    Route::get('/wazne-informacje', InfoController::class)->name('important.info');

    Route::post('/contactform.store', [ContactFormController::class, 'store'])->name('contactform.store');
    Route::post('/caretakerrecommendation.store', [CaretakerRecommendationController::class, 'store'])->name('caretakerrecommendation.store');

    Route::post('/payoutrequest.store', [PayoutRequestController::class, 'store'])->name('payoutrequest.store');

    Route::get('/pointstopayout', function () {
        return PointsSettingsPayout::find(1)->pluck('points_to_payout')[0];
    })->name('pointstopayout');

    Route::get('/payoutcash', function () {
        return CashSettingsPayout::find(1)->pluck('payout_cash')[0];
    })->name('payoutcash');

    Route::post('/crm.offer.get', [CRMOfferDownloadController::class, 'download'])->name('crm.offer.get');

    Route::get('/usercurrentpoints', function () {
        return UserProfile::where('user_id', '=', Auth::user()->id)->pluck('current_points')[0];
    })->name('usercurrentpoints');

    Route::controller(CRMDocumentController::class)->group(function () {
        Route::name('crm.document.')->group(function () {
            Route::get('/dokumenty', 'index')->name('index');
            Route::get('/dokumenty/umowa-i-a1', 'contract_and_a1_check')->name('check');
        });
        // Route::get('/dokumenty', 'index')->name('crm.document.index');
    });

    // Route::get('/dokumenty', [CRMDocumentController::class, 'index'])->name('crm.document.index');



    Route::controller(CookieController::class)->name('cookie.')->group(function () {
        Route::post('/set_alert_cookie', 'set_alert_cookie')->name('set_alert_cookie');
    });

    Route::get('/account.types', function () {
        return AccountType::all();
    })->name('account.types.index');
});
