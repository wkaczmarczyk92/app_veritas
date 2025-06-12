<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\MediaLibraryStoreRequest;
use App\Models\Common\FileMimeType;

use App\Services\File\FileStoreService;
use App\Models\Common\File;

use App\Models\Common\FileType;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use Exception;
use Illuminate\Support\Facades\Storage;

class MediaLibraryController extends Controller
{
    public function index()
    {
        // if (Storage::disk('media_library')->exists('/onevvvvvvvvvvvvvv.png')) {
        //     echo '1';
        //     Storage::disk('media_library')->delete('/onevvvvvvvvvvvvvv.png');
        // }
        // return;
        $layout = Auth::user()->hasRole(['admin', 'super-admin']) ? 'AdminLayout' : 'CourseModeratorLayout';

        return Inertia::render('Common/MediaLibrary', [
            'layout' => $layout,
            'mime_types' => FileMimeType::all()->pluck('type')
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $file = File::find($request->file['id']);
            $file->update([
                'name' => $request->file['name'],
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Coś poszło nie tak podczas aktualizacji pliku. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Plik został zaktualizowany pomyślnie.');
    }

    public function generate_hash(int $id)
    {
        dd($id);
    }

    public function store(MediaLibraryStoreRequest $request)
    {
        return (new FileStoreService())->store($request->files_data, FileType::where('type', 'media_library')->value('id'), 'media_library');
    }

    public function get(Request $request)
    {
        $offset = $request->offset ?? 0;

        $data = [
            'files_count' => File::where('file_type_id', FileType::where('type', 'media_library')->value('id'))->count(),
            'files' => File::with(['type', 'mime_type', 'user', 'user.user_profiles'])
                ->where('file_type_id', FileType::where('type', 'media_library')->value('id'))
                ->orderBy('created_at', 'desc')
                ->offset($offset)
                ->limit(10)
                ->get(),
        ];

        $data['end'] = $data['files_count'] <= $offset + 10;
        return $data;
    }
}
