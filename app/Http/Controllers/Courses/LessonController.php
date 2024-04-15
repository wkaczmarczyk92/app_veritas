<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Response;
use App\Http\Requests\Compendium\StoreRequest;

use App\Services\Lesson\LessonStoreService;
use App\Services\Lesson\LessonOrderUpdateService;
use App\Services\Lesson\LessonUpdateService;
use App\Services\Lesson\LessonDestroyService;

use Inertia\Inertia;

use App\Models\Courses\Compendium;
use App\Models\Courses\Lesson;

use App\Models\Common\Visibility;

class LessonController extends Controller
{
    public function create(int $compendium_id)
    {
        $compendium = Compendium::find($compendium_id);

        if (!$compendium) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Lesson/CreateForm', [
            'compendium' => $compendium,
            'visibility' => Visibility::all()
        ]);
    }

    public function create_with_vademecum(int $vademecum_id)
    {
        $vademecum = Compendium::find($vademecum_id);

        if (!$vademecum) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Lesson/CreateFormForVademecum', [
            'vademecum' => $vademecum,
            'visibility' => Visibility::all()
        ]);
    }

    public function show_with_compendium(int $compendium_id, int $lesson_id)
    {
        $lesson = Lesson::with(['lessonable', 'visibility'])->find($lesson_id);

        if (!$lesson) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Lesson/ShowWithCompendium', [
            'lesson' => $lesson
        ]);
    }

    public function show_with_vademecum(int $compendium_id, int $lesson_id)
    {
        $lesson = Lesson::with(['lessonable', 'visibility'])->find($lesson_id);

        if (!$lesson) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Lesson/ShowWithVademecum', [
            'lesson' => $lesson
        ]);
    }

    public function store_with_compendium(StoreRequest $request, int $id)
    {
        return (new LessonStoreService)->store_with_compendium($request->all(), Compendium::find($id));
    }

    public function edit_with_compendium(int $compendium_id, int $lesson_id)
    {
        $lesson = Lesson::with('lessonable')->find($lesson_id);

        if (!$lesson) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Lesson/EditWithCompendium', [
            'lesson' => $lesson,
            'visibility' => Visibility::all()
        ]);
    }

    public function edit_with_vademecum(int $vademecum_id, int $lesson_id)
    {
        $lesson = Lesson::with('lessonable')->find($lesson_id);

        if (!$lesson) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Lesson/EditWithVademecum', [
            'lesson' => $lesson,
            'visibility' => Visibility::all()
        ]);
    }

    public function update_with_compendium(StoreRequest $request, int $id)
    {
        $lesson = Lesson::with('lessonable')->find($id);

        if (!$lesson) {
            abort(404, 'Not found');
        }

        return (new LessonUpdateService)->update_with_compendium($request->all(), $lesson);
    }

    public function update_order_with_compendium(Request $request, int $id)
    {
        return (new LessonOrderUpdateService)->update_order_with_compendium($request->lessons, Compendium::find($id));
    }

    public function destroy(int $id)
    {
        return (new LessonDestroyService)->destroy(Lesson::find($id));
    }
}
