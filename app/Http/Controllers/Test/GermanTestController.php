<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GermanTests\GermanTestStoreService;
use App\Services\GermanTests\GermanTestCheckResultService;
use App\Services\GermanTests\GermanTestDestroyService;
use App\Services\GermanTests\GermanTestGetQuestionsService;
use App\Models\Courses\GermanLesson;

use Inertia\Inertia;

use App\Helpers\Transaction;

class GermanTestController extends Controller
{
    public function load_from_file(Request $request)
    {
        return (new GermanTestStoreService)($request);
    }

    public function index() {}

    public function settings()
    {
        return Inertia::render('Lessons/GermanLessons/TestSettings', [
            'german_lesson' => GermanLesson::find(1)
        ]);
    }

    public function update_settings(Request $request)
    {
        return Transaction::try(
            function () use ($request) {
                $german_lesson = GermanLesson::find(1);
                $german_lesson->update([
                    'test_time' => $request->test_time
                ]);
            },
            'Ustawienia zostały zaktualizowane.'
        );
    }

    public function check_result(Request $request)
    {
        return (new GermanTestCheckResultService)($request);
    }

    public function destroy(int $id)
    {
        return (new GermanTestDestroyService)($id);
    }

    public function show()
    {
        $questions = (new GermanTestGetQuestionsService)();

        return Inertia::render('Lessons/GermanLessons/Test', [
            'questions' => $questions
        ]);
    }
}
