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
use App\Http\Controllers\LastLoginController;

use App\Http\Controllers\Lessons\GermanLessonController;

use App\Http\Controllers\Test\GermanTestController;
use App\Http\Controllers\Test\QuestionController;
use App\Http\Controllers\Test\TestResultController;

use App\Models\BonusStatus;
use App\Http\Controllers\Settings\UpdateCaretakerDataController;

use App\Http\Controllers\Admin\AuthMimicController;
use App\Http\Controllers\Test\OralExamController;

Route::middleware(['auth', 'role:user|super-admin|god_mode|admin'])
    ->group(function () {
        Route::controller(AuthMimicController::class)
            ->prefix('imitacja-uwierzytelnienia')
            ->name('auth.mimic.')
            ->group(function () {
                Route::post('powrot-do-panelu-admina', 'back_to_admin_panel')
                    ->name('back.to.admin.panel');
            });

        Route::controller(OralExamController::class)
            ->prefix('egzamin-ustny')
            ->name('oral.exam.')
            ->group(function () {
                Route::post('harmonogram', 'download_selected_date')->name('download.selected.date');
                Route::post('zapisz-termin', 'store')->name('store');
                Route::delete('usun-terminy', 'destroy')->name('destroy');
                Route::post('zalicz-test', 'set_as_passed')->name('set.as.passed');
                Route::post('oblej-test', 'set_as_failure')->name('set.as.failure');
            });
    });

Route::middleware(['auth', 'role:super-admin|god_mode'])->group(function () {
    Route::controller(UpdateCaretakerDataController::class)
        ->prefix('ustawienia-zaawansowane')
        ->name('advance.settings.')
        ->group(function () {
            Route::prefix('opiekunki')
                ->name('caretakers.')
                ->group(function () {
                    Route::post('aktualizacja-rekruterow', 'update_caretakers_recruiter')
                        ->name('update.recruiter');
                });
        });

    Route::controller(OralExamController::class)
        ->prefix('egzamin-ustny')
        ->name('oral.exam.')
        ->group(function () {
            Route::get('harmonogram', 'index_create')->name('index.create');
            Route::get('wyniki', 'index')->name('index');
        });

    Route::controller(AuthMimicController::class)
        ->prefix('imitacja-uwierzytelnienia')
        ->name('auth.mimic.')
        ->group(function () {
            Route::post('zaloguj-sie-jako-uzytkownik', 'login_as_user')
                ->name('login.as.user');
        });
});

Route::middleware(['auth', 'role:admin|super-admin|god_mode'])->group(function () {
    Route::controller(GermanTestController::class)
        ->prefix('testy-niemieckiego')
        ->name('german.tests.')
        ->group(function () {
            Route::get('wczytaj-test-z-pliku-xml', 'load_from_xml')
                ->name('load.from.xml');

            Route::post('wrzuc-z-pliku', 'load_from_file')
                ->name('upload.from.file');

            Route::delete('usun-test/{id}', 'destroy')
                ->name('destroy');

            Route::get('ustawienia', 'settings')
                ->name('settings');

            Route::patch('ustawienia', 'update_settings')
                ->name('settings.update');

            Route::get('test', 'show')
                ->name('show');
        });

    // Route::controller(TestResultController::class)
    //     ->prefix('harmonogram-egzaminow-ustnych')
    //     ->name('test.results.')
    //     ->group(function () {

    //         Route::get('', '')
    //             ->name('');
    //     });

    Route::controller(TestResultController::class)
        ->prefix('wyniki-testow')
        ->name('test.results.')
        ->group(function () {
            Route::delete('usun-wyniki-testowe', 'destroy_test_user_data')
                ->name('destroy.test.user.data');

            Route::get('testy-niemieckiego', 'index')
                ->name('index');
        });

    Route::controller(QuestionController::class)
        ->prefix('pytania')
        ->name('questions.')
        ->group(function () {
            Route::post('dodaj-audio', 'upload_audio')
                ->name('upload.audio');
            Route::delete('usun-audio/{file_id}', 'destroy_audio')
                ->name('audio.destroy');
        });

    // Route::controller(TestController::class)->prefix('kursy/testy')->name('test.controller.')->group(function () {

    // });

    Route::controller(GermanLessonController::class)
        ->prefix('admin/lekcje-niemieckiego')
        ->name('german.lessons.')
        ->group(function () {
            Route::get('/', 'index')
                ->name('index');

            Route::get('/dodaj-lekcje', 'create')
                ->name('create');

            Route::post('/dodaj-lekcje', 'store')
                ->name('store');

            Route::get('/lekcja/{id}', 'show')
                ->name('show');

            Route::get('/lekcja/edytuj/{id}', 'edit')
                ->name('edit');

            Route::delete('/lekcja/usun/{id}', 'destroy')
                ->name('destroy');

            Route::patch('lekcja/aktualizuj-widocznosc', 'update_visibility')
                ->name('update.visibility');
        });

    Route::get('/pulpit', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::controller(OfferController::class)
        ->name('offer.')
        ->group(function () {
            Route::get('/zgloszenia-na-oferty', 'index')
                ->name('index');

            Route::delete('/usun-oferte/{id}', 'destroy')
                ->name('destroy');
        });

    // USER

    Route::controller(UserController::class)
        ->group(function () {
            Route::get('/uzytkownicy', 'index')
                ->name('users');

            Route::get('/uzytkownik/{id}', 'show')
                ->name('user');

            Route::patch('/uzytkownik', 'update')
                ->name('user.update');

            Route::patch('uzytkownik/{user_id}/aktywacja-konta-premium', 'promote_to_premium')
                ->name('user.promote.to.premium');

            Route::post('pobierz-uzytkownikow', 'admin_search_index')
                ->name('users.admin.search.index');
        });
    // Route::get('/uzytkownicy', [UserController::class, 'index'])->name('users');

    // Route::get('/uzytkownik/{id}', [UserController::class, 'show'])->name('user');

    // Route::patch('/uzytkownik', [UserController::class, 'update'])->name('user.update');


    // PUNKTY
    Route::post('/userpoints.store', [UserPointController::class, 'store'])
        ->name('userpoints.store');
    Route::post('/userpoints.activate/{user_id}', [UserPointController::class, 'activate_by_admin'])
        ->name('userpoints.activate.by.admin');


    // CHECKPOINTS

    Route::controller(PointCheckpointController::class)
        ->group(function () {
            Route::get('/ustawienia-punktow-kontrolnych', 'index')
                ->name('pointbreakpoints.index');

            Route::patch('/checkpoints.update', 'update')
                ->name('checkpoints.update');
        });

    // BOK REQUEST
    Route::get('/zgloszenia-do-boku', [BOKRequestController::class, 'index'])
        ->name('bokrequest.index');

    Route::post('/load-bok-requests/{id}', function (int $id) {
        return response()->json([
            BOKRequest::with(['user.user_profiles', 'subject'])
                ->where('user_id', '=', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'bok_request')
        ]);
    })->name('load.bok.requests.for.user');


    // PAYOUT REQUEST
    Route::controller(PayoutRequestController::class)
        ->prefix('wnioski-o-wyplate')
        ->name('payout.requests.')
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::patch('/aktualizuj', 'update')
                ->name('update');

            Route::patch('aktualizuj-status', 'change_payout_status')
                ->name('update.status');

            Route::prefix('policz')
                ->name('count.')
                ->group(function () {
                    Route::get('/w-trakcie-realizacji', function () {
                        return PayoutRequest::with('user_has_bonus')
                            ->whereHas('user_has_bonus', function ($query) {
                                $query->where('bonus_status_id', BonusStatus::where('name', 'in_progress')->value('id'));
                            })->count();
                    })->name('in.progress');

                    Route::get('/do-akceptacji', function () {
                        return PayoutRequest::with('user_has_bonus')
                            ->whereHas('user_has_bonus', function ($query) {
                                $query->where('bonus_status_id', BonusStatus::where('name', 'for_approval')->value('id'));
                            })->count();
                    })->name('for.approval');
                });

            Route::post('/uzytkownik/{id}', function (int $id) {
                return response()->json([PayoutRequest::with([
                    'user_has_bonus.user.user_profiles',
                    'user_has_bonus.status',
                    'user_has_bonus.level',
                ])
                    ->whereHas('user_has_bonus', function ($query) use ($id) {
                        $query->where('user_id', $id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)]);
            })->name('user');

            Route::prefix('pobierz')
                ->name('get.')
                ->group(function () {
                    Route::post('w-trakcie-realizacji/{bonus_status}', 'get')->name('in.progress');

                    Route::post('/zrealizowane', function (Request $request) {
                        return response()->json([PayoutRequest::with(['admin_user.user_profiles', 'user_has_bonus.user.user_profiles'])
                            ->whereHas('user_has_bonus', function ($query) {
                                $query->where('bonus_status_id', BonusStatus::where('name', 'completed')->value('id'));
                            })
                            ->orderBy('updated_at', 'desc')
                            ->paginate(10, ['*'], 'complete_page')]);
                    })->name('completed');

                    Route::post('/do-akceptacji', function (Request $request) {
                        return response()->json([PayoutRequest::with(['admin_user.user_profiles', 'user_has_bonus.user.user_profiles'])
                            ->whereHas('user_has_bonus', function ($query) {
                                $query->where('bonus_status_id', BonusStatus::where('name', 'for_approval')->value('id'));
                            })
                            ->orderBy('updated_at', 'desc')
                            ->paginate(10, ['*'], 'for_approval_page')]);
                    })->name('for.approval');
                });
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

        Route::name('caretaker.recommendations.')->prefix('polecenia-opiekunek')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::patch('/update-bonus-payout', 'updateBonusPayout')->name('update.bonus.payout');
            Route::post('odblokowane', 'count_unlocked')->name('count.unlocked');
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
