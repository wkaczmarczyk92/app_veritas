<?php

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboards\RecruiterDashboardController;

Route::middleware(['auth', 'role:recruiter'])->group(function () {
    Route::get('/rekruter/pulpit', [RecruiterDashboardController::class, 'dashboard'])->name('recruiter.dashboard');
});
