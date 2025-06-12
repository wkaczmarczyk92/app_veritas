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

use App\Models\BonusStatus;

use App\Http\Controllers\LastLoginController;
use App\Http\Controllers\Test\GermanTestController;
use App\Http\Controllers\Test\TestController;

use App\Http\Controllers\Lessons\GermanLessonController;
use App\Http\Controllers\Test\QuestionController;

Route::middleware(['auth', 'role:admin|super-admin'])->group(function () {
    Route::controller(GermanTestController::class)->prefix('testy-niemieckiego')->name('german.tests.')->group(function () {
        Route::get('wczytaj-test-z-pliku-xml', 'load_from_xml')->name('load.from.xml');
        Route::post('wrzuc-z-pliku', 'load_from_file')->name('upload.from.file');
        Route::delete('usun-test/{id}', 'destroy')->name('destroy');
        Route::get('ustawienia', 'settings')->name('settings');
        Route::patch('ustawienia', 'update_settings')->name('settings.update');

        Route::get('test', 'show')->name('show');
    });

    Route::controller(QuestionController::class)->prefix('pytania')->name('questions.')->group(function () {
        Route::post('dodaj-audio', 'upload_audio')->name('upload.audio');
        Route::delete('usun-audio/{file_id}', 'destroy_audio')->name('audio.destroy');
    });

    // Route::controller(TestController::class)->prefix('kursy/testy')->name('test.controller.')->group(function () {

    // });

    Route::controller(GermanLessonController::class)->prefix('admin/lekcje-niemieckiego')->name('german.lessons.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dodaj-lekcje', 'create')->name('create');
        Route::post('/dodaj-lekcje', 'store')->name('store');
        Route::get('/lekcja/{id}', 'show')->name('show');
        Route::get('/lekcja/edytuj/{id}', 'edit')->name('edit');
        Route::delete('/lekcja/usun/{id}', 'destroy')->name('destroy');
    });

    Route::get('/pulpit', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/zgloszenia-na-oferty', [OfferController::class, 'index'])->name('offer.index');

    // USER

    Route::controller(UserController::class)->group(function () {
        Route::get('/uzytkownicy', 'index')->name('users');
        Route::get('/uzytkownik/{id}', 'show')->name('user');
        Route::patch('/uzytkownik', 'update')->name('user.update');
    });
    // Route::get('/uzytkownicy', [UserController::class, 'index'])->name('users');

    // Route::get('/uzytkownik/{id}', [UserController::class, 'show'])->name('user');

    // Route::patch('/uzytkownik', [UserController::class, 'update'])->name('user.update');


    // PUNKTY
    Route::post('/userpoints.store', [UserPointController::class, 'store'])->name('userpoints.store');
    Route::post('/userpoints.activate/{user_id}', [UserPointController::class, 'activate_by_admin'])->name('userpoints.activate.by.admin');


    // CHECKPOINTS

    Route::controller(PointCheckpointController::class)->group(function () {
        Route::get('/ustawienia-punktow-kontrolnych', 'index')->name('pointbreakpoints.index');
        Route::patch('/checkpoints.update', 'update')->name('checkpoints.update');
    });

    // BOK REQUEST
    Route::get('/zgloszenia-do-boku', [BOKRequestController::class, 'index'])->name('bokrequest.index');

    Route::post('/load-bok-requests/{id}', function (int $id) {
        return response()->json([
            BOKRequest::with(['user.user_profiles', 'subject'])
                ->where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'bok_request')
        ]);
    })->name('load.bok.requests.for.user');


    // PAYOUT REQUEST
    Route::controller(PayoutRequestController::class)->group(function () {
        Route::get('/wnioski-o-wyplate', 'index')->name('payoutrequest.index');
        Route::patch('/payoutrequests.update', 'update')->name('payoutrequests.update');

        Route::get('/payout-request-count', function () {
            return PayoutRequest::with('user_has_bonus')
                ->whereHas('user_has_bonus', function ($query) {
                    $query->where('bonus_status_id', BonusStatus::where('name', 'in_progress')->value('id'));
                })->count();
        })->name('payout.count.incomplete');

        Route::get('/payout-request-count-for-approval', function () {
            return PayoutRequest::with('user_has_bonus')
                ->whereHas('user_has_bonus', function ($query) {
                    $query->where('bonus_status_id', BonusStatus::where('name', 'for_approval')->value('id'));
                })->count();
        })->name('payout.count.for.approval');

        Route::post('/load-payout-requests/{id}', function (int $id) {
            return response()->json([PayoutRequest::with(['user_has_bonus.user.user_profiles'])
                ->whereHas('user_has_bonus', function ($query) use ($id) {
                    $query->where('user_id', $id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)]);
        })->name('load.payout.requests.for.user');

        Route::post('/load-incomplete-payout-requests', function (Request $request) {
            return response()->json([PayoutRequest::with(['user_has_bonus.user.user_profiles'])
                ->whereHas('user_has_bonus', function ($query) {
                    $query->where('bonus_status_id', BonusStatus::where('name', 'in_progress')->value('id'));
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'incomplete_page')]);
        })->name('load.incomplete.payout.requests');

        Route::post('/load-complete-payout-requests', function (Request $request) {
            return response()->json([PayoutRequest::with(['admin_user.user_profiles', 'user_has_bonus.user.user_profiles'])
                ->whereHas('user_has_bonus', function ($query) {
                    $query->where('bonus_status_id', BonusStatus::where('name', 'completed')->value('id'));
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'complete_page')]);
        })->name('load.complete.payout.requests');

        Route::post('/load-for-approval-payout-requests', function (Request $request) {
            return response()->json([PayoutRequest::with(['admin_user.user_profiles', 'user_has_bonus.user.user_profiles'])
                ->whereHas('user_has_bonus', function ($query) {
                    $query->where('bonus_status_id', BonusStatus::where('name', 'for_approval')->value('id'));
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'for_approval_page')]);
        })->name('load.for.approval.payout.requests');
    });

    // POSTY
    Route::controller(PostController::class)->group(function () {
        Route::name('post.')->group(function () {
            Route::get('/posty', 'index')->name('index');
            Route::get('/dodaj-post', 'create')->name('create');
            Route::post('/post.store', 'store')->name('store');
            Route::patch('/update.post.order', 'updateOrder')->name('order.update');
            Route::delete('/post/{post}', 'destroy')->name('destroy');

            Route::get('/post/{post}', 'edit')->name('edit');
            Route::patch('/post/{post}', 'update')->name('update');

            Route::get('/post.all', function () {
                return Post::with('post_labels')
                    ->orderBy('order')
                    ->get();
            })->name('all');
        });
    });

    // CONTACT FORMS
    Route::post('/formularze-kontaktowe/user_id/{user_id}', function (int $id) {
        return response()->json([
            ContactForm::where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
        ]);
    })->name('contact.forms.user');

    // FAMILY RECOMMENDATIONS
    Route::post('/rekomendacje-rodzin/user_id/{user_id}', function (int $id) {
        return response()->json([
            FamilyRecommendation::with('user.user_profiles')
                ->where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
        ]);
    })->name('family.recommendations.user');

    Route::get('/polecenia-rodzin', [FamilyRecommendationController::class, 'index'])->name('family.recommendations.index');


    // CARETAKER RECOMMENDATIONS
    Route::post('/rekomendacje-opiekunek/user_id/{user_id}', function (int $id) {
        return response()->json([
            CaretakerRecommendation::with('user.user_profiles')
                ->where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(15)
        ]);
    })->name('caretaker.recommendations.user');

    Route::controller(CaretakerRecommendationController::class)->group(function () {
        Route::name('caretaker.recommendation.')->group(function () {
            Route::delete('/caretaker.recommendation/{id}', 'destroy')->name('destroy');
            Route::patch('/caretaker.recommendation.update', 'update')->name('update');
        });

        Route::name('caretaker.recommendations.')->group(function () {
            Route::get('/polecenia-opiekunek', 'index')->name('index');
            Route::get('/polecenia-opiekunek/{id}', 'show')->name('show');
            Route::patch('/polecenia-opiekunek/update-bonus-payout', 'updateBonusPayout')->name('update.bonus.payout');
        });
    });

    // USER PROFILE IMAGE
    Route::controller(UserProfileImageController::class)->group(function () {
        Route::patch('/accept.user.profile.img', 'accept')->name('accept.user.profile.img');
        Route::patch('/decline.user.profile.img', 'decline')->name('decline.user.profile.img');

        Route::name('user.profile.')->group(function () {
            Route::post('/user.profile.image.index', 'index')->name('image.index');
            Route::patch('/user.profile.image.mass.accept', 'massAccept')->name('image.mass.accept');
        });

        Route::get('/weryfikacja-zdjec-profilowych', 'verify')->name('user.profiles.verify');
        Route::delete('/usun-wybrane-zdjecia', 'decline_2')->name('decline.user.profile.image.2');
    });

    Route::post('/count.unverified.profile.img', function () {
        return UserProfileImage::where('status', 1)->count();
    })->name('count.unverified.profile.img');

    // Route::get('/pobierz-opiekunki', [CRMCaretakerController::class, 'index']);
    // Route::get('/pobierz-rekruterow', [CRMRecruiterController::class, 'index']);
    // Route::get('/aktualizuj-id-opiekunek', [CRMCaretakerController::class, 'updateCaretakerId']);


    // CRM USER PROFILE IMAGE
    Route::post('/download.crm.profile.image/{user_id}', [DownloadCRMProfileImage::class, 'download'])->name('download.crm.profile.image');


    // PASSWORD REQUEST
    Route::controller(PasswordRequestController::class)->group(function () {
        Route::name('password.')->group(function () {
            Route::post('/password.request.count', 'count')->name('request.count');
            Route::get('/zgloszenia-zmiany-hasla', 'index')->name('request.index');
            Route::patch('/password.request.update', 'update')->name('request.update');
        });
    });

    Route::get('/ostatnie-logowania', [LastLoginController::class, 'index'])->name('last.login.index');
});
