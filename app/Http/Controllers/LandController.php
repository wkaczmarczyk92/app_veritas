<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Land;

class LandController extends Controller
{
    public function index() {
        return response()->json(Land::all());
    }
}
