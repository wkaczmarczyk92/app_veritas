<?php

namespace App\Http\Controllers;

use App\Models\BOKSubject;

class BOKSubjectController extends Controller
{
    public function index()
    {
        return response()->json(BOKSubject::all());
    }
}
