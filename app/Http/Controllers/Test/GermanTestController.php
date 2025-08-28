<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GermanTests\GermanTestStoreService;
use App\Services\GermanTests\GermanTestCheckResultService;
use App\Services\GermanTests\GermanTestDestroyService;
use App\Services\GermanTests\GermanTestGetQuestionsService;
use App\Models\Courses\GermanLesson;
use App\Models\Test\Test;

use Inertia\Inertia;

use App\Helpers\Transaction;
use App\Models\Test\OralExam;

use App\Http\Requests\GermanLesson\UpdateTestTimeRequest;
use App\Models\Test\TestResult;

use App\Models\Common\SeenInfo;

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

    public function update_settings(UpdateTestTimeRequest $request)
    {
        return Transaction::try(
            function () use ($request) {
                $german_lesson = GermanLesson::find(1);
                $german_lesson->update([
                    'test_time' => $request->test_time,
                    'question_count' => $request->question_count
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

    public function questions() {
        $german_lesson = GermanLesson::find(1);
        $questions = (new GermanTestGetQuestionsService)($german_lesson->question_count);
        return response()->json(['questions' => $questions]);
    }

    public function show()
    {
        $german_lesson = GermanLesson::find(1);
        $questions = (new GermanTestGetQuestionsService)($german_lesson->question_count);

        $is_admin = auth()->user()->hasAnyRole(['admin', 'super-admin', 'god_mode']);
        $user = auth()->user();
        $user->load([
            'test_results' => function ($query) {
                $query->where('test_id', Test::where('name', 'Test niemieckiego')->value('id'));
            }
        ]);

        $view = $is_admin ? 'Lessons/GermanLessons/Test' : 'Lessons/GermanLessons/User/Test';
        $test_attempts = TestResult::where('user_id', auth()->id())->whereDate('created_at', date('Y-m-d'))->where('test_id', Test::where('name', 'Test niemieckiego')->value('id'))->count();
        $test_passed = $user->test_results->contains('is_passed', true);
        $can_take_test = $is_admin || ($test_attempts < 2 && !$test_passed);
        $oral_exam_passed = OralExam::where('is_passed', true)->where('user_id', auth()->id())->count();
        $has_any_oral_exam = OralExam::whereNull('is_passed')->where('user_id', auth()->id())->count();
        $current_oral_exam = OralExam::where('user_id', auth()->id())->whereNull('is_passed')->first();

        $arr = [
            'questions' => $questions,
            'german_lesson' => $german_lesson,
            'can_take_test' => $can_take_test,
            'test_attempts' => $test_attempts,
            'test_passed' => $test_passed,
            'oral_exam_passed' => (bool)$oral_exam_passed,
            'has_any_oral_exam' => (bool)$has_any_oral_exam,
            'current_oral_exam' => $current_oral_exam,
        ];

        return Inertia::render($view, $arr);
    }

    public function become_mittel_program() {
        $user = auth()->user();
        $user->load(['seen_infos' => function ($query) {
            $query->where('info_type', 'become_mittel_program');
        }]);

        if ($user->seen_infos->isEmpty()) {
            $user->seen_infos()->save(new SeenInfo([
                'info_type' => 'become_mittel_program',
                'seen' => true
            ]));
        }

        return Inertia::render('Lessons/GermanLessons/User/BecomeMittel');
    }
}
