<?php

namespace App\Services\GermanTests;

use App\Helpers\Transaction;
use App\Models\Test\Test;
use App\Models\Test\Question;

use App\Models\Test\TestResult;

class GermanTestCheckResultService
{
    public $percent_needed_to_pass_test = 80;

    public function __invoke($request)
    {
        return Transaction::try(
            function () use ($request) {
                if ($request->test_id) {
                    $test = Test::with([
                        'questions',
                        'questions.closed_choices',
                    ])->find($request->test_id);

                    $questions = $test->questions;
                } else {
                    $questions_id = array_column($request->test, 'id');
                    $questions = Question::with('closed_choices')->whereIn('id', $questions_id)->get();
                }

                $answers = [];

                foreach ($request->test_answer as $answer) {
                    $current_question = $questions->filter(fn($item) => $item->id == $answer['question_id'])->values()[0];
                    $current_correct_id = $current_question->closed_choices->filter(fn($item) => $item->is_correct == true)->values()[0]->id;

                    $answers[$answer['question_id']] = $answer['answer_id'] == $current_correct_id;
                }

                $count_good_answers = count(array_filter(
                    $answers,
                    fn($item) => $item === true
                ));

                $good_answers_percent = (100 * $count_good_answers) / count($questions);

                if (!auth()->user()->hasAnyRole(['admin', 'super-admin', 'god_mode'])) {
                    TestResult::create([
                        'test_id' => Test::where('name', 'Test niemieckiego')->value('id'),
                        'user_id' => auth()->id(),
                        'is_passed' => $this->percent_needed_to_pass_test <= $good_answers_percent,
                        'score' => number_format((float)$good_answers_percent, 0)
                    ]);
                }

                return [
                    'good_answers' => $count_good_answers,
                    'good_answers_percent' => number_format((float)$good_answers_percent, 0),
                    'passed' => $this->percent_needed_to_pass_test <= $good_answers_percent
                ];
            },
            'Wynik został sprawdzony.'
        );
    }
}
