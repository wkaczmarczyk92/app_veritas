<?php

namespace App\Services\GermanTests;

use App\Helpers\Transaction;
use App\Models\Courses\Lesson;
use App\Models\Test\Question;
use App\Models\Test\QuestionClosedChoice;
use App\Models\Test\QuestionType;
use App\Models\Test\Test;
use Illuminate\Support\Facades\Auth;

class GermanTestStoreService
{
    public function __invoke($request)
    {
        return Transaction::try(function () use ($request) {
            $file = $request->file('test');
            $xmlString = file_get_contents($file->getRealPath());
            $xml = simplexml_load_string($xmlString);

            $test = Test::create([
                'created_by' => Auth::id(),
                'name' => (string)$xml->NazwaTestu,
                'visibility_id' => 1,
                'time' => null
            ]);

            $counter = 1;
            $answers = [];

            foreach ($xml->Pytania->Pytanie as $question) {
                $new_question = Question::create([
                    'test_id' => $test->id,
                    'created_by' => Auth::id(),
                    'order' => $counter++,
                    'question' => (string)$question->TrescPytania[0],
                    'time' => $question->Czas,
                    'question_type_id' => QuestionType::where('type', $question->TypPytania)->first()->id
                ]);

                $correct_id = $question->PoprawnaOdpowiedz;

                foreach ($question->Odpowiedzi->Odpowiedz as $answer) {
                    $new_question_choice = QuestionClosedChoice::create([
                        'choice' => (string)$answer,
                        'question_id' => $new_question->id,
                        'multiple_answers' => false,
                        'is_correct' => $correct_id == (string)$answer['id']
                    ]);

                    $answers[] = $new_question_choice;
                }
            }

            $lesson = Lesson::find($request->lesson_id);
            $lesson->test()->save($test);

            $test->load([
                'questions.closed_choices',
                'questions',
                'questions.type',
                'questions.file',
            ]);

            return [
                'test' => $test
            ];
        }, 'Test został załadowany i przypisany do lekcji.');
    }
}
