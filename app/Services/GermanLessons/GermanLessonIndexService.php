<?php

namespace App\Services\GermanLessons;

use Inertia\Inertia;
use App\Models\Courses\GermanLesson;

class GermanLessonIndexService
{
    public function __invoke()
    {
        $admin = auth()->user()->hasAnyRole(['admin', 'super-admin']);

        $german_lesson = GermanLesson::with([
            'lessons' => function ($query) use ($admin) {
                if (!$admin) {
                    $query->where('visibility_id', 3)->where('order', '!=', 999);
                }

                $query->orderBy('order', 'asc');
            },
            'lessons.visibility',
            'lessons.files',
            'lessons.files',
            'lessons.files.user',
            'lessons.files.type',
            'lessons.user',
            'test',
            'test.questions',
            'test.questions.closed_choices',
        ])->find(1);

        $template = !$admin ? 'Lessons/GermanLessons/User/Index' : 'Lessons/GermanLessons/Index';

        return Inertia::render($template, [
            'german_lesson' => $german_lesson
        ]);
    }
}
