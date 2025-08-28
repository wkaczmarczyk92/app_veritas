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

        $is_user = auth()->user()->hasRole('user');

        if ($is_user) {
            if ($lesson->visibility_id != 3) {
                return redirect()->route('user.german.lessons.index');
            }

            $previous_lesson = Lesson::where('order', '<', $lesson->order)
                ->where('visibility_id', 3)
                ->where('lessonable_type', 'App\Models\Courses\GermanLesson')
                ->orderBy('order', 'desc')
                ->first();

            $next_lesson = Lesson::where('order', '>', $lesson->order)
                ->where('visibility_id', 3)
                ->where('lessonable_type', 'App\Models\Courses\GermanLesson')
                ->where('order', '!=', 999)
                ->orderBy('order', 'asc')
                ->first();
        }

        return Inertia::render($template, [
            'lesson' => $lesson,
            'visibilities' => Visibility::whereIn('id', [1, 3])->get(),
            'previous_lesson' => $previous_lesson ?? null,
            'next_lesson' => $next_lesson ?? null,
            'is_last_lesson' => ($next_lesson ?? null) ? false : true,
        ]);
    }
}
