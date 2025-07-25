<?php

namespace App\Services\GermanTests;

use App\Models\Courses\GermanLesson;
use App\Models\Courses\Lesson;

class GermanTestGetQuestionsService
{
    public function __invoke($question_count)
    {
        $german_lesson = GermanLesson::with([
            'lessons' => function ($query) {
                $query->where('visibility_id', 3);
            },
            'lessons.test.questions',
            'lessons.test.questions.type',
            'lessons.test.questions.file',
            'lessons.test.questions.closed_choices',
        ])->first();

        $questions = $german_lesson->lessons->flatMap(function (Lesson $lesson) {
            if ($lesson->test[0]) {
                return $lesson->test[0]->questions;
            }

            return collect();
        })->shuffle()->take($question_count);

        return $questions;
    }
}
