<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Transaction;
use App\Models\Test\TestResult;

use Inertia\Inertia;

class TestResultController extends Controller
{
    public function destroy_test_user_data()
    {
        return Transaction::try(
            function () {
                TestResult::where('user_id', 37831)->delete();
            },
            'Dane zostały usunięte.'
        );
    }

    public function index()
    {
        $test_results = TestResult::with([
            'user',
            'user.user_profiles',
            'user.user_profile_image',
            'test'
        ])->get();

        // dd($test_results);

        return Inertia::render('Admin/TestResults/Index', [
            'test_results' => $test_results
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/TestResults/Create');
    }
}
