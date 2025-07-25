<?php

namespace App\Http\Controllers\Lessons;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Http\Requests\Lesson\LessonStoreRequest;
use App\Models\Courses\Lesson;
use App\Services\GermanLessons\GermanLessonDestroyService;
use App\Services\GermanLessons\GermanLessonStoreService;
use App\Services\GermanLessons\GermanLessonIndexService;
use App\Http\Requests\GermanLesson\UpdateVisibilityRequest;

use App\Services\GermanLessons\GermanLessonShowService;
use App\Services\GermanLessons\GermanLessonVisibilityUpdateService;

class GermanLessonController extends Controller
{
    public function index()
    {
        return (new GermanLessonIndexService)();
    }

    public function show(int $id)
    {
        return (new GermanLessonShowService())($id);
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

    public function update_visibility(UpdateVisibilityRequest $request)
    {
        return (new GermanLessonVisibilityUpdateService)($request->lesson_id, $request->visibility_id);
    }

}
