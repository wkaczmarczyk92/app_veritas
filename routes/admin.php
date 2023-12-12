<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Models\BOKRequest;
use App\Models\PayoutRequest;
use App\Models\Post;
use App\Models\ContactForm;
use App\Models\FamilyRecommendation;
use App\Models\CaretakerRecommendation;
use App\Models\UserProfileImage;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPointController;
use App\Http\Controllers\BOKRequestController;
use App\Http\Controllers\PayoutRequestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FamilyRecommendationController;
use App\Http\Controllers\CaretakerRecommendationController;
use App\Http\Controllers\UserProfileImageController;
use App\Http\Controllers\PasswordRequestController;
use App\Http\Controllers\DownloadCRMProfileImage;
use App\Http\Controllers\PointCheckpointController;

use App\Http\Controllers\OfferController;

use App\Http\Controllers\CRMCaretakerController;

use App\Http\Controllers\LastLoginController;

Route::middleware(['auth', 'role:admin|super-admin'])->group(function() {
    Route::get('/pulpit', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/zgloszenia-na-oferty', [OfferController::class, 'index'])->name('offer.index');

    // USER
    Route::get('/uzytkownicy', [UserController::class, 'index'])->name('users');

    Route::get('/uzytkownik/{id}', [UserController::class, 'show'])->name('user');

    Route::patch('/uzytkownik', [UserController::class, 'update'])->name('user.update');


    // PUNKTY
    Route::post('/userpoints.store', [UserPointController::class, 'store'])->name('userpoints.store');


    // CHECKPOINTS
    Route::get('/ustawienia-punktow-kontrolnych', [PointCheckpointController::class, 'index'])->name('pointbreakpoints.index');

    Route::patch('/checkpoints.update', [PointCheckpointController::class, 'update'])->name('checkpoints.update');


    // BOK REQUEST
    Route::get('/zgloszenia-do-boku', [BOKRequestController::class, 'index'])->name('bokrequest.index');

    Route::post('/load-bok-requests/{id}', function(int $id) {
        return response()->json([
            BOKRequest::with(['user.user_profiles', 'subject'])
                ->where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'bok_request')
        ]);
    })->name('load.bok.requests.for.user');


    // PAYOUT REQUEST
    Route::get('/wnioski-o-wyplate', [PayoutRequestController::class, 'index'])->name('payoutrequest.index');
    
    Route::patch('/payoutrequests.update', [PayoutRequestController::class, 'update'])->name('payoutrequests.update');

    Route::get('/payout-request-count', function() {
        return PayoutRequest::with('user_has_bonus')
            ->whereHas('user_has_bonus', function($query) {
                $query->where('completed', false)
                    ->where('in_progress', true);
            })->count();
    })->name('payout.count.incomplete');

    Route::post('/load-payout-requests/{id}', function(int $id) {
        return response()->json([PayoutRequest::with(['user_has_bonus.user.user_profiles'])
            ->whereHas('user_has_bonus', function($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)]);
    })->name('load.payout.requests.for.user');

    Route::post('/load-incomplete-payout-requests', function(Request $request) {
        return response()->json([PayoutRequest::with(['user_has_bonus.user.user_profiles'])
            ->whereHas('user_has_bonus', function($query) {
                $query->where('completed', false)
                    ->where('in_progress', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'incomplete_page')]);
    })->name('load.incomplete.payout.requests');

    Route::post('/load-complete-payout-requests', function(Request $request) {
        return response()->json([PayoutRequest::with(['admin_user.user_profiles', 'user_has_bonus.user.user_profiles'])
            ->whereHas('user_has_bonus', function($query) {
                $query->where('completed', true)
                    ->where('in_progress', true);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'complete_page')]);
    })->name('load.complete.payout.requests');


    // POSTY
    Route::get('/posty', [PostController::class, 'index'])->name('post.index');

    Route::get('/dodaj-post', [PostController::class, 'create'])->name('post.create');

    Route::post('/post.store', [PostController::class, 'store'])->name('post.store');

    Route::patch('/update.post.order', [PostController::class, 'updateOrder'])->name('post.order.update');

    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::get('/post.all', function() {
        return Post::with('post_labels')
        ->orderBy('order')
        ->get();
    })->name('post.all');

    Route::get('/post/{post}', [PostController::class, 'edit'])->name('post.edit');

    Route::patch('/post/{post}', [PostController::class, 'update'])->name('post.update');


    // CONTACT FORMS
    Route::post('/formularze-kontaktowe/user_id/{user_id}', function(int $id) {
        return response()->json([
            ContactForm::where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
        ]);
    })->name('contact.forms.user');

    // FAMILY RECOMMENDATIONS
    Route::post('/rekomendacje-rodzin/user_id/{user_id}', function(int $id) {
        return response()->json([
            FamilyRecommendation::with('user.user_profiles')
                ->where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
        ]);
    })->name('family.recommendations.user');

    Route::get('/polecenia-rodzin', [FamilyRecommendationController::class, 'index'])->name('family.recommendations.index');


    // CARETAKER RECOMMENDATIONS
    Route::post('/rekomendacje-opiekunek/user_id/{user_id}', function(int $id) {
        return response()->json([
            CaretakerRecommendation::with('user.user_profiles')
                ->where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
        ]);
    })->name('caretaker.recommendations.user');

    Route::get('/polecenia-opiekunek', [CaretakerRecommendationController::class, 'index'])->name('caretaker.recommendations.index');

    Route::get('/polecenia-opiekunek/{id}', [CaretakerRecommendationController::class, 'show'])->name('caretaker.recommendations.show');

    Route::patch('/polecenia-opiekunek/update-bonus-payout', [CaretakerRecommendationController::class, 'updateBonusPayout'])->name('caretaker.recommendations.update.bonus.payout');

    Route::patch('/caretaker.recommendation.update', [CaretakerRecommendationController::class, 'update'])->name('caretaker.recommendation.update');


    // USER PROFILE IMAGE
    Route::patch('/accept.user.profile.img', [UserProfileImageController::class, 'accept'])->name('accept.user.profile.img');
    Route::patch('/decline.user.profile.img', [UserProfileImageController::class, 'decline'])->name('decline.user.profile.img');

    Route::post('/count.unverified.profile.img', function() {
        return UserProfileImage::where('status', 1)->count();
    })->name('count.unverified.profile.img');

    Route::get('/weryfikacja-zdjec-profilowych', [UserProfileImageController::class, 'verify'])->name('user.profiles.verify');
    Route::post('/user.profile.image.index', [UserProfileImageController::class, 'index'])->name('user.profile.image.index');
    Route::patch('/user.profile.image.mass.accept', [UserProfileImageController::class, 'massAccept'])->name('user.profile.image.mass.accept');

    Route::get('/pobierz-opiekunki', [CRMCaretakerController::class, 'index']);
    // Route::get('/pobierz-rekruterow', [CRMRecruiterController::class, 'index']);
    // Route::get('/aktualizuj-id-opiekunek', [CRMCaretakerController::class, 'updateCaretakerId']);


    // CRM USER PROFILE IMAGE
    Route::post('/download.crm.profile.image/{user_id}', [DownloadCRMProfileImage::class, 'download'])->name('download.crm.profile.image');


    // PASSWORD REQUEST
    Route::post('/password.request.count', [PasswordRequestController::class, 'count'])->name('password.request.count');

    Route::get('/zgloszenia-zmiany-hasla', [PasswordRequestController::class, 'index'])->name('password.request.index');

    Route::patch('/password.request.update', [PasswordRequestController::class, 'update'])->name('password.request.update');


    Route::get('/ostatnie-logowania', [LastLoginController::class, 'index'])->name('last.login.index');
});