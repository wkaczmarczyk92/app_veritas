<?php

namespace App\Services\File;

use Illuminate\Support\Facades\DB;
use App\Models\Common\File;
use App\Models\Common\FileMimeType;
use App\Helpers\Response;

use Illuminate\Support\Facades\Hash;

class FileStoreService
{
    private $mime_types_map = [];

    public function store($files, $file_type_id, $disk = 'public')
    {
        DB::beginTransaction();
        $mime_types = FileMimeType::all();

        foreach ($mime_types as $type) {
            $this->mime_types_map[$type->type] = $type->id;
        }

        try {
            $this->_multiple($files, $file_type_id, $disk);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Wystąpił błąd podczas przesyłania plików. Spróbuj ponownie.', e: $e);
        }

        return Response::success('Pliki zostały przesłane.');
    }

    private function _storage_save()
    {
    }

    private function _multiple($files, $file_type_id, $disk)
    {
        $current_hashes = [];

        foreach ($files as $file) {
            $file_extension = $file['file']->getClientOriginalExtension();
            $file['file']->storeAs('', "{$file['name']}.{$file_extension}", $disk);
            $pre_path = '/media_library/';

            do {
                $hash = Hash::make($file['name'] . time());
                $hash = '[' . substr($hash, 0, 50) . ']';
            } while (File::where('hash', $hash)->exists() and !in_array($hash, $current_hashes));

            $current_hashes[] = $hash;

            $new_file = File::create([
                'name' => $file['name'],
                'path' => "{$pre_path}{$file['name']}.{$file_extension}",
                'extension' => $file_extension,
                'size' => $file['file']->getSize(),
                'file_mime_type_id' => $this->mime_types_map[$file['file']->getMimeType()] ?? null,
                // 'media_library' => true,
                'file_type_id' => $file_type_id,
                'hash' => $hash
            ]);

            if (isset($file['opt_for']) and $file['opt_for'] != null) {
                $new_file->opt_fors()->attach($file['opt_for']);
            }
        }
    }

    private function _single($file)
    {
    }
}
