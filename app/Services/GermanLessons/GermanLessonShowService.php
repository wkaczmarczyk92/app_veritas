<?php

namespace App\Services\GermanLessons;

use Inertia\Inertia;
use App\Models\Courses\Lesson;
use App\Models\Common\Visibility;

class GermanLessonShowService
{
    public function __invoke(int $id)
    {
        $admin = auth()->user()->hasAnyRole(['admin', 'super-admin']);
        $template = $admin ? 'Lessons/GermanLessons/Show' : 'Lessons/GermanLessons/User/Show';

        $lesson = Lesson::with([
            'visibility',
            'files',
            'files',
            'files.user',
            'files.type',
            'user',
            'test',
            'test.questions',
            'test.questions.type',
            'test.questions.file',
            'test.questions.closed_choices',
        ])->find($id);

        return Inertia::render($template, [
            'lesson' => $lesson,
            'visibilities' => Visibility::whereIn('id', [1, 3])->get()
        ]);
    }
}
