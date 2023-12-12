<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BOKSubject;

class BOKSubjectController extends Controller
{
    public function index()
    {
        return response()->json(BOKSubject::all());
    }
}
