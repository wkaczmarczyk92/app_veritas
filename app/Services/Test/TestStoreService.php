<?php

namespace App\Services\Test;

use App\Models\Test\Test;
use App\Models\Test\Question;
use App\Models\Test\QuestionClosedChoice;
use App\Models\Test\QuestionMatch;

use Illuminate\Support\Facades\DB;
use App\Helpers\Response;

use App\Models\Test\QuestionType;

class TestStoreService
{
    public function store($test)
    {
        DB::beginTransaction();;

        try {
            $new_test = Test::create([
                'name' => $test['name'],
                'time' => $test['time'],
                'visibility_id' => $test['visibility_id']
            ]);

            if (!empty($test['permissions']['roles'])) {
                $new_test->roles()->attach($test['permissions']['roles']);
            }

            if (!empty($test['permissions']['company_branches'])) {
                $new_test->company_branches()->attach($test['permissions']['company_branches']);
            }

            $order = 1;
            $question_types = QuestionType::all()->toArray();

            foreach ($test['questions'] as $q) {
                $question = new Question([
                    'question' => $q['question'],
                    'question_type_id' => $q['type_id'],
                    'order' => $order++
                ]);

                $new_test->questions()->save($question);

                switch (array_values(array_filter($question_types, fn ($qt) => $qt['id'] === $q['type_id']))[0]['type']) {
                    case 'closed';
                        foreach ($q['choices'] as $cc) {
                            $closed_choice = new QuestionClosedChoice([
                                'choice' => $cc['answer'],
                                'is_correct' => $cc['is_correct']
                            ]);

                            $question->closed_choices()->save($closed_choice);
                        }
                        break;
                    case 'closed_multiple':
                        foreach ($q['choices'] as $cc) {
                            $closed_choice = new QuestionClosedChoice([
                                'choice' => $cc['answer'],
                                'is_correct' => $cc['is_correct'],
                                'multiple_answers' => true
                            ]);

                            $question->closed_choices()->save($closed_choice);
                        }
                        break;

                    case 'match':
                        foreach ($q['matches'] as $m) {
                            $match = new QuestionMatch([
                                'match_text' => $m['match_text'],
                                'answer' => $m['answer']
                            ]);

                            $question->matches()->save($match);
                        }
                        break;
                }
            }

            if (!empty($test['class_and_id'])) {
                $class_and_id = explode('|', $test['class_and_id']);
                $class = $class_and_id[0];
                $id = $class_and_id[1];

                DB::table('model_has_tests')->insert([
                    'test_id' => $new_test->id,
                    'model_type' => $class,
                    'model_id' => $id
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Dodanie testu nie powiodło się. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Test został dodany.');
    }
}
