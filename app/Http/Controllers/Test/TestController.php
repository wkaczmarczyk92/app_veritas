<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

use App\Models\Common\CompanyBranch;
use Spatie\Permission\Models\Role;

use App\Models\Courses\Compendium;
use App\Models\Courses\Lesson;

use App\Models\Common\Visibility;

use App\Models\Test\QuestionType;
use App\Http\Requests\Test\TestStoreRequest;

use App\Services\Test\TestStoreService;
use App\Services\Test\TestDestroyService;
use App\Models\Test\Test;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{


    public function course_moderator_index()
    {
        return Inertia::render('CourseModerator/Test/Index');
    }

    public function list(Request $request)
    {
        // dd(Test::with(['lesson', 'compendium', 'visibility'])
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(50));
        return response()->json([
            'tests' => Test::with(['lesson.lessonable', 'compendium', 'visibility', 'roles', 'company_branches'])
                ->withCount('questions')
                ->orderBy('created_at', 'desc')
                ->paginate(50)
        ]);
    }

    public function course_moderator_show(int $id)
    {
        $test = Test::with([
            'questions' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'questions.closed_choices',
            'questions.matches',
            'questions.type',
            'visibility',
            'roles',
            'company_branches',
            'lesson.lessonable',
            'compendium',
            'questions'
        ])
            ->withCount('questions')
            ->find($id);
        // dd($test);

        return Inertia::render('CourseModerator/Test/Show', [
            'test' => $test
        ]);
    }

    public function course_moderator_create()
    {
        $lessons = Lesson::all();
        $lessons = $lessons->map(function ($item) {
            return [
                'value' => get_class($item) . '|' . $item->id,
                'name' => $item->name
            ];
        })->toArray();

        $compendiums = Compendium::all();
        $compendiums = $compendiums->map(function ($item) {
            return [
                'value' => get_class($item) . '|' . $item->id,
                'name' => $item->name
            ];
        })->toArray();

        $merged = array_merge($lessons, $compendiums);

        return Inertia::render('CourseModerator/Test/CreateForm', [
            'roles' => Role::where('show_course', true)->get(),
            'company_branches' => CompanyBranch::all(),
            'merged' => $merged,
            'question_types' => QuestionType::all(),
            'visibility' => Visibility::all()
        ]);
    }

    public function store(TestStoreRequest $request)
    {
        return (new TestStoreService())->store($request->all());
    }

    public function destroy(int $id)
    {
        return (new TestDestroyService())->destroy($id);
    }
}
