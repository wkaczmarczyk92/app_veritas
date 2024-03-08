<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\APIUserController;
use App\Http\Controllers\API\APIReadyToDepartureController;
use App\Http\Controllers\API\APIFamilyRecommendationController;
use App\Http\Controllers\FamilyRecommendationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('token')->group(function () {
    Route::patch('/user.update', [APIUserController::class, 'update'])->name('api.user.update');

    Route::post('/user.store', [APIUserController::class, 'store'])->name('api.user.store');

    Route::patch('/ready.to.departure.update', [APIReadyToDepartureController::class, 'update'])->name('api.ready.to.departure.update');

    Route::patch('/test-api', [APIUserController::class, 'test'])->name('test');

    Route::patch('/family.recommendation.update.bonus.payout.complete', [APIFamilyRecommendationController::class, 'update'])->name('family.recommendation.update.bonus.payout.complete');

    Route::delete('/family.recommendation.destroy', [FamilyRecommendationController::class, 'destroy'])->name('family.recommendation.destroy');
});
