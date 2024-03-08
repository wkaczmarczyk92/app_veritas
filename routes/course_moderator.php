<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboards\CourseModeratorDashboardController;
use App\Http\Controllers\UserController;

Route::middleware(['auth', 'role:course_moderator'])->prefix('/moderator-kursow')->group(function () {
    Route::get('/pulpit', [CourseModeratorDashboardController::class, 'dashboard'])->name('course_moderator.dashboard');


    Route::get('/dodaj-uzytkownika', [UserController::class, 'create_as_course_moderator'])->name('course_moderator.create_user');
    Route::get('/uzytkownicy', [UserController::class, 'create_as_course_moderator'])->name('course_moderator.users');
});
