<?php

namespace App\Services\GermanTests;

use App\Models\Courses\GermanLesson;
use App\Models\Courses\Lesson;

class GermanTestGetQuestionsService
{
    public function __invoke()
    {
        $german_lesson = GermanLesson::with([
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
        })->shuffle()->take(20);

        return $questions;
    }
}
