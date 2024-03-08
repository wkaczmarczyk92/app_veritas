<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Models\UserPoint;
use App\Models\CaretakerRecommendation;
use App\Models\FamilyRecommendation;

use App\Http\Controllers\TestController;
use App\Http\Controllers\UserProfileImageController;
use App\Http\Controllers\UserPointController;
use App\Http\Controllers\PasswordRequestController;

use App\Http\Controllers\OneTimeSMSPasswordController;

use App\Http\Controllers\LandController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'role:user|admin|super-admin'])->group(function () {


    Route::get('/user', [UserController::class, 'get'])->name('user.get');


    Route::post('/store.or.update.user.profile.image', [UserProfileImageController::class, 'storeOrUpdate'])->name('store.or.update.user.profile.image');

    Route::post('/count.user.points.records/{user_id}', function (int $id) {
        return response()->json(
            UserPoint::where('user_id', $id)->count()
        );
    })->name('count.user.points.records');

    Route::post('/user.caretaker.recommendations/{user_id}', function (Request $request, int $id) {
        return response()->json(
            CaretakerRecommendation::where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        );
    })->name('user.caretaker.recommendations');

    Route::post('/user.family.recommendations/{user_id}', function (Request $request, int $id) {
        return response()->json(
            FamilyRecommendation::where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        );
    })->name('user.family.recommendations');

    Route::post('/count.user.caretaker.recommendations/{user_id}', function (int $id) {
        return response()->json(
            CaretakerRecommendation::where('user_id', $id)->count()
        );
    })->name('count.user.caretaker.recommendations');

    Route::post('/count.user.family.recommendations/{user_id}', function (int $id) {
        return response()->json(
            FamilyRecommendation::where('user_id', $id)->count()
        );
    })->name('count.user.family.recommendations');

    Route::post('/punkty', [UserPointController::class, 'index'])->name('points.index');


    Route::get('/land.index', [LandController::class, 'index'])->name('land.index');



    Route::get('/user.point.last.insert.date', [UserPointController::class, 'last_insert_date'])->name('user.point.last.insert.date');
});

// Route::get('/test', [TestController::class, 'overdueCRON'])->name('test');
// Route::get('/svg-test', [TestController::class, 'svg_test']);
// Route::get('/phpversion', [TestController::class, 'php_version'])->name('phpversion');

Route::post('/password.store', [PasswordRequestController::class, 'store'])->name('password.store');
Route::post('/one.time.sms.password.store', [OneTimeSMSPasswordController::class, 'store'])->name('one.time.sms.password.store');
Route::patch('/one.time.sms.password.update', [OneTimeSMSPasswordController::class, 'update'])->name('one.time.sms.password.update');
Route::patch('/submit.new.pass.with.sms.code', [OneTimeSMSPasswordController::class, 'new_password'])->name('submit.new.pass.with.sms.code');

require __DIR__ . '/auth.php';
require __DIR__ . '/superadmin.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/cron.php';
require __DIR__ . '/course_moderator.php';
require __DIR__ . '/recruiter.php';
