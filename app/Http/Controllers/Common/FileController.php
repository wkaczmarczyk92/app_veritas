<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

use App\Services\File\CourseFileUploadService;
use App\Services\File\FileDestroyService;
use App\Models\Options\OptFor;

use App\Http\Requests\File\CourseFilesStoreRequest;

use App\Models\Common\File;
use App\Models\Common\FileType;

use App\Services\File\FileStoreService;

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
        // return (new CourseFileUploadService())->upload($request->files_data);
        return (new FileStoreService())->store($request->files_data, FileType::where('type', 'course_files_for_users')->value('id'));
    }

    public function course_moderator_list()
    {
        $caretaker_files = File::with('opt_fors')
            ->whereHas('opt_fors', function ($query) {
                $query->where('name', 'opiekunka');
            })
            ->where('file_type_id', FileType::where('type', 'course_files_for_users')->value('id'))
            ->get();

        $recruiter_files = File::with('opt_fors')
            ->whereHas('opt_fors', function ($query) {
                $query->where('name', 'rekruter');
            })
            ->where('file_type_id', FileType::where('type', 'course_files_for_users')->value('id'))
            ->get();

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
