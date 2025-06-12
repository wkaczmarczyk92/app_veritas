<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboards\CourseModeratorDashboardController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\CRMRecruiterController;
use App\Http\Controllers\Courses\CompendiumController;
use App\Models\Courses\Compendium;

use App\Http\Controllers\Courses\LessonController;
use App\Http\Controllers\Courses\VideoFramedController;

use App\Http\Controllers\Common\FileController;

use App\Http\Controllers\Test\TestController;

Route::middleware(['auth', 'role:course_moderator'])->prefix('/moderator-kursow')->name('course_moderator.')->group(function () {
    Route::get('/pulpit', [CourseModeratorDashboardController::class, 'dashboard'])->name('dashboard');

    Route::controller(UserController::class)->group(function () {
        Route::get('/dodaj-uzytkownika', 'create_as_course_moderator')->name('create_user');
        Route::get('/uzytkownicy', 'index_course_moderator')->name('users');
        Route::get('/users.recruiters.get', 'recruiters')->name('users.recruiters.get');

        Route::name('user.')->group(function () {
            Route::post('/user.store', 'store_as_course_moderator')->name('store');
            Route::get('/uzytkownik/{id}', 'course_moderator_show')->name('show');
            Route::post('/uzytkownik/{id}/crm_id/update', 'update_crm_id')->name('crm_id.update');
        });
    });


    Route::post('/crm-recruiter/search', [CRMRecruiterController::class, 'search_in_crm'])->name('crm_recruiter.search');


    Route::controller(CompendiumController::class)->group(function () {
        Route::name('compendium.')->group(function () {
            Route::prefix('kompendia')->group(function () {
                Route::get('/dodaj', 'create_compendium')->name('create');
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store_compendium')->name('store');

                Route::get('/list', 'list')->name('list');
            });

            Route::prefix('kompendium')->group(function () {
                Route::get('/{id}', 'show')->name('show');

                Route::delete('/usun/{id}', 'destroy')->name('destroy');

                Route::get('/edytuj/{id}', 'edit')->name('edit');
                Route::patch('/update/{id}', 'update')->name('update');
            });
        });


        Route::name('vademecum.')->group(function () {
            Route::prefix('vademecum')->group(function () {
                Route::get('/dodaj', 'create_vademecum')->name('create');
                Route::post('/store', 'store_vademecum')->name('store');
                Route::get('/{id}', 'show_vademecum')->name('show');
                Route::get('/edytuj/{id}', 'edit_vademecum')->name('edit');
            });

            Route::get('/vademeca', 'index_vademeca')->name('index');
        });
    });

    Route::controller(LessonController::class)->group(function () {
        Route::name('compendium.lesson.')->group(function () {
            Route::prefix('kompendium')->group(function () {
                Route::get('/{compendium_id}/lekcje/dodaj', 'create')->name('create');
                Route::post('/{id}/lekcje/dodaj', 'store_with_compendium')->name('store');
                Route::patch('/{id}/lekcje/order/update', 'update_order_with_compendium')->name('order.update');
                Route::get('/{compendium_id}/lekcja/{lesson_id}', 'show_with_compendium')->name('show');
                Route::get('/{compendium_id}/lekcja/{lesson_id}/edytuj', 'edit_with_compendium')->name('edit');
            });

            Route::patch('/lekcja/{id}/update', 'update_with_compendium')->name('update');
        });

        Route::name('vademecum.lesson.')->group(function () {
            Route::get('/vademecum/{vademecum_id}/lekcje/dodaj', 'create_with_vademecum')->name('create');
            // Route::post('/vademecum/{id}/lekcje/dodaj', [LessonController::class, 'store_with_vademecum'])->name('vademecum.lesson.store');

            Route::get('/vademecum/{vademecum_id}/lekcja/{lesson_id}', 'show_with_vademecum')->name('show');
            Route::get('/vademecum/{vademecum_id}/lekcja/{lesson_id}/edytuj', 'edit_with_vademecum')->name('edit');
        });

        Route::delete('/lekcja/usun/{id}', 'destroy')->name('lesson.destroy');
    });

    Route::controller(VideoFramedController::class)
        ->prefix('webinaria')
        ->name('video.')
        ->group(function () {
            Route::get('/webinaria', 'index')->name('index');
            Route::post('/webinaria/dodaj', 'store')->name('store');
            Route::get('/webinaria/lista', 'list')->name('list');
            Route::delete('/webinaria/usun/{video}', 'destroy')->name('destroy');

            Route::patch('/webinaria/{id}/update', 'update')->name('update');
        });

    Route::controller(FileController::class)
        ->name('files.')
        ->prefix('pliki')
        ->group(function () {
            Route::get('/', 'course_moderator_index')->name('index');
            Route::post('/pliki/update', 'course_moderator_update')->name('update');

            Route::get('/pliki/lista', 'course_moderator_list')->name('list');
            Route::delete('/pliki/usun/{id}', 'course_moderator_destroy')->name('destroy');
        });

    Route::controller(TestController::class)
        ->name('test.')
        ->group(function () {
            Route::prefix('testy')->group(function () {
                Route::get('/dodaj', 'course_moderator_create')->name('create');
                Route::post('/dodaj', 'store')->name('store');

                Route::get('/', 'course_moderator_index')->name('index');
                Route::post('/lista', 'list')->name('list');
            });

            Route::prefix('test')
                ->group(function () {
                    Route::get('/test', 'list')->name('list.test');
                    Route::get('/{id}', 'course_moderator_show')->name('show');

                    Route::delete('/usun/{id}', 'destroy')->name('destroy');
                });
        });
});
