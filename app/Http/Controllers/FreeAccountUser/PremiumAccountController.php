<?php

namespace App\Http\Controllers\FreeAccountUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

class PremiumAccountController extends Controller
{
    public function index()
    {
        return Inertia::render('FreeAccount/PremiumAccount');
    }
}
