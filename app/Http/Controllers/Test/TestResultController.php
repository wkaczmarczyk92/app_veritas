<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Transaction;
use App\Models\Test\TestResult;

class TestResultController extends Controller
{
    public function destroy_test_user_data() {
        return Transaction::try(function () {
            TestResult::where('user_id', 37831)->delete();
        },
        'Dane zostały usunięte.');
    }
}
