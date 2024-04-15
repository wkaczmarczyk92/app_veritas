<?php

namespace App\Services\File;

use Illuminate\Support\Facades\DB;
use App\Models\Common\File;

use App\Helpers\Response;
use Illuminate\Support\Facades\Storage;

class CourseFileUploadService
{
    public function upload($files)
    {
        DB::beginTransaction();

        try {
            foreach ($files as $file) {
                // var_dump($file);
                $file_extension = $file['file']->getClientOriginalExtension();
                // Storage::disk('public')->put("{$file['name']}.{$file_extension}", $file['file']);
                $file['file']->storeAs('public', "{$file['name']}.{$file_extension}");

                $new_file = File::create([
                    'name' => $file['name'],
                    'path' => "/uploads/{$file['name']}.{$file_extension}",
                    'extension' => $file_extension,
                    'size' => $file['file']->getSize(),
                ]);

                $new_file->opt_fors()->attach($file['opt_for']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Wystąpił błąd podczas przesyłania plików. Spróbuj ponownie.', e: $e);
        }

        return Response::success('Pliki zostały przesłane.');
    }
}
