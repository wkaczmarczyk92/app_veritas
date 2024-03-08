<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

class RecruiterDashboardController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Recruiter/Dashboard');
    }
}
