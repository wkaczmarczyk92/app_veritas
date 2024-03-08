<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

class CourseModeratorDashboardController extends Controller
{
    public function dashboard()
    {
        // dd(1111);
        return Inertia::render('CourseModerator/Dashboard');
    }
}
