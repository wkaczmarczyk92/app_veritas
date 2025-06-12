<?php

namespace App\Http\Controllers\Lessons;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Http\Requests\Lesson\LessonStoreRequest;
use App\Models\Courses\Lesson;
use App\Models\Courses\GermanLesson;
use App\Services\GermanLessons\GermanLessonDestroyService;
use App\Services\GermanLessons\GermanLessonStoreService;
use App\Services\GermanLessons\GermanLessonIndexService;

class GermanLessonController extends Controller
{
    public function index()
    {
        return (new GermanLessonIndexService)();
    }

    public function show(int $id)
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
            'lesson' => $lesson
        ]);
    }

    public function edit(int $id)
    {
        $lesson = Lesson::with([
            'visibility',
            'files',
            'files',
            'files.user',
            'files.type',
            'user'
        ])->find($id);

        return Inertia::render('Lessons/GermanLessons/Edit', [
            'lesson' => $lesson
        ]);
    }

    public function destroy(int $id)
    {
        return (new GermanLessonDestroyService())($id);
    }

    public function create()
    {
        return Inertia::render('Lessons/GermanLessons/Create');
    }

    public function store(LessonStoreRequest $request)
    {
        return (new GermanLessonStoreService())($request);
    }
}
