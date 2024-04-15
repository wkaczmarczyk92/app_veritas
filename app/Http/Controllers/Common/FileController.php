<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

use App\Services\File\CourseFileUploadService;
use App\Services\File\FileDestroyService;
use App\Models\Options\OptFor;

use App\Http\Requests\File\CourseFilesStoreRequest;

use App\Models\Common\File;

class FileController extends Controller
{
    public function course_moderator_index()
    {
        return Inertia::render('CourseModerator/Files/Index', [
            'opt_for' => OptFor::all()
        ]);
    }

    public function course_moderator_update(CourseFilesStoreRequest $request)
    {
        return (new CourseFileUploadService())->upload($request->files_data);
    }

    public function course_moderator_list()
    {
        $caretaker_files = File::with('opt_fors')
            ->whereHas('opt_fors', function ($query) {
                $query->where('name', 'opiekunka');
            })->get();

        $recruiter_files = File::with('opt_fors')
            ->whereHas('opt_fors', function ($query) {
                $query->where('name', 'rekruter');
            })->get();

        return response()->json([
            'caretaker' => $caretaker_files,
            'recruiter' => $recruiter_files
        ]);
    }

    public function course_moderator_destroy(int $id)
    {
        return (new FileDestroyService)->destroy($id);
    }
}
