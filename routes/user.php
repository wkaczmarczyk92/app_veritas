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

use App\Http\Controllers\OfferController;

Route::middleware(['auth', 'role:user'])->group(function() {
    Route::get('/bok-subjects', [BOKSubjectController::class, 'index'])->name('boksubject.index');
    Route::post('/bok-request', [BOKRequestController::class, 'store'])->name('bokrequest.store');
    
    Route::get('/', [HomePageController::class, 'index'])->name('/');
    
    Route::post('/readytodeparturedate.store.or.update', [ReadyToDepartureDateController::class, 'storeOrUpdate'])->name('readytodeparturedate.store.or.update');
    Route::patch('/readytodeparturedate.destroy', [ReadyToDepartureDateController::class, 'destroy'])->name('readytodeparturedate.destroy');
    
    Route::post('/contactform.store', [ContactFormController::class, 'store'])->name('contactform.store');
    Route::post('/caretakerrecommendation.store', [CaretakerRecommendationController::class, 'store'])->name('caretakerrecommendation.store');

    Route::post('/familyrecommendation.store', [FamilyRecommendationController::class, 'store'])->name('familyrecommendation.store');
    Route::post('/payoutrequest.store', [PayoutRequestController::class, 'store'])->name('payoutrequest.store');


    Route::get('/pointstopayout', function() {
        return PointsSettingsPayout::find(1)->pluck('points_to_payout')[0];
    })->name('pointstopayout');

    Route::get('/payoutcash', function() {
        return CashSettingsPayout::find(1)->pluck('payout_cash')[0];
    })->name('payoutcash');

    Route::post('/crm.offer.get', [CRMOfferDownloadController::class, 'download'])->name('crm.offer.get');

    Route::post('/offer.store', [OfferController::class, 'store'])->name('offer.store');

    Route::get('/usercurrentpoints', function() {
        return UserProfile::where('user_id', '=', Auth::user()->id)->pluck('current_points')[0];
    })->name('usercurrentpoints');
});