<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\CourseSearchController;

Route::middleware('auth')->group(function () {
    Route::post('/course.search', CourseSearchController::class)->name('course.search');
});
