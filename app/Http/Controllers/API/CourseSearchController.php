<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Search\CourseSearchService;

class CourseSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json((new CourseSearchService())->search($request->input('query')));
    }
}
