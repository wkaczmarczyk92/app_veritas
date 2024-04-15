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

    Route::get('/dodaj-uzytkownika', [UserController::class, 'create_as_course_moderator'])->name('create_user');
    Route::get('/uzytkownicy', [UserController::class, 'index_course_moderator'])->name('users');

    Route::post('/crm-recruiter/search', [CRMRecruiterController::class, 'search_in_crm'])->name('crm_recruiter.search');
    Route::post('/user.store', [UserController::class, 'store_as_course_moderator'])->name('user.store');

    Route::get('/users.recruiters.get', [UserController::class, 'recruiters'])->name('users.recruiters.get');
    Route::get('/uzytkownik/{id}', [UserController::class, 'course_moderator_show'])->name('user.show');

    Route::post('/uzytkownik/{id}/crm_id/update', [UserController::class, 'update_crm_id'])->name('user.crm_id.update');

    Route::get('/kompendia/dodaj', [CompendiumController::class, 'create_compendium'])->name('compendium.create');
    Route::get('/kompendia', [CompendiumController::class, 'index'])->name('compendium.index');
    Route::post('/kompendia/store', [CompendiumController::class, 'store_compendium'])->name('compendium.store');

    Route::get('/kompendia/list', [CompendiumController::class, 'list'])->name('compendium.list');
    Route::get('/kompendium/{id}', [CompendiumController::class, 'show'])->name('compendium.show');

    Route::delete('/kompendium/usun/{id}', [CompendiumController::class, 'destroy'])->name('compendium.destroy');

    Route::get('/kompendium/edytuj/{id}', [CompendiumController::class, 'edit'])->name('compendium.edit');
    Route::patch('/kompendium/update/{id}', [CompendiumController::class, 'update'])->name('compendium.update');

    Route::get('/kompendium/{compendium_id}/lekcje/dodaj', [LessonController::class, 'create'])->name('compendium.lesson.create');
    Route::post('/kompendium/{id}/lekcje/dodaj', [LessonController::class, 'store_with_compendium'])->name('compendium.lesson.store');

    Route::patch('/kompendium/{id}/lekcje/order/update', [LessonController::class, 'update_order_with_compendium'])->name('compendium.lesson.order.update');

    Route::get('/kompendium/{compendium_id}/lekcja/{lesson_id}', [LessonController::class, 'show_with_compendium'])->name('compendium.lesson.show');
    Route::get('/kompendium/{compendium_id}/lekcja/{lesson_id}/edytuj', [LessonController::class, 'edit_with_compendium'])->name('compendium.lesson.edit');
    Route::patch('/lekcja/{id}/update', [LessonController::class, 'update_with_compendium'])->name('compendium.lesson.update');

    Route::get('/vademecum/dodaj', [CompendiumController::class, 'create_vademecum'])->name('vademecum.create');
    Route::post('/vademecum/store', [CompendiumController::class, 'store_vademecum'])->name('vademecum.store');


    Route::get('/vademecum/{id}', [CompendiumController::class, 'show_vademecum'])->name('vademecum.show');
    Route::get('/vademeca', [CompendiumController::class, 'index_vademeca'])->name('vademecum.index');


    Route::get('/vademecum/edytuj/{id}', [CompendiumController::class, 'edit_vademecum'])->name('vademecum.edit');

    Route::get('/vademecum/{vademecum_id}/lekcje/dodaj', [LessonController::class, 'create_with_vademecum'])->name('vademecum.lesson.create');
    // Route::post('/vademecum/{id}/lekcje/dodaj', [LessonController::class, 'store_with_vademecum'])->name('vademecum.lesson.store');

    Route::get('/vademecum/{vademecum_id}/lekcja/{lesson_id}', [LessonController::class, 'show_with_vademecum'])->name('vademecum.lesson.show');
    Route::get('/vademecum/{vademecum_id}/lekcja/{lesson_id}/edytuj', [LessonController::class, 'edit_with_vademecum'])->name('vademecum.lesson.edit');

    Route::delete('/lekcja/usun/{id}', [LessonController::class, 'destroy'])->name('lesson.destroy');


    Route::get('/webinaria', [VideoFramedController::class, 'index'])->name('video.index');
    Route::post('/webinaria/dodaj', [VideoFramedController::class, 'store'])->name('video.store');
    Route::get('/webinaria/lista', [VideoFramedController::class, 'list'])->name('video.list');
    Route::delete('/webinaria/usun/{video}', [VideoFramedController::class, 'destroy'])->name('video.destroy');

    Route::patch('/webinaria/{id}/update', [VideoFramedController::class, 'update'])->name('video.update');


    Route::get('/pliki', [FileController::class, 'course_moderator_index'])->name('files.index');
    Route::post('/pliki/update', [FileController::class, 'course_moderator_update'])->name('files.update');

    Route::get('/pliki/lista', [FileController::class, 'course_moderator_list'])->name('files.list');
    Route::delete('/pliki/usun/{id}', [FileController::class, 'course_moderator_destroy'])->name('files.destroy');


    Route::get('/testy/dodaj', [TestController::class, 'course_moderator_create'])->name('test.create');
    Route::post('/testy/dodaj', [TestController::class, 'store'])->name('test.store');

    Route::get('/testy', [TestController::class, 'course_moderator_index'])->name('test.index');
    Route::post('/testy/lista', [TestController::class, 'list'])->name('test.list');

    Route::get('/test/test', [TestController::class, 'list'])->name('test.list.test');
    Route::get('/test/{id}', [TestController::class, 'course_moderator_show'])->name('test.show');

    Route::delete('/test/usun/{id}', [TestController::class, 'destroy'])->name('test.destroy');
});
