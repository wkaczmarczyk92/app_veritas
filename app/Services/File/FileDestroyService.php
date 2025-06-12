<?php

namespace App\Services\File;

use App\Models\Common\File;
use Illuminate\Support\Facades\DB;

use App\Helpers\Response;
use Illuminate\Support\Facades\Storage;

class FileDestroyService
{
    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();
            $file = File::with('type')->find($id);

            $disk = $file->file_type_id == 1 ? 'public' : 'media_library';
            Storage::disk($disk)->delete(str_replace('/uploads', '', $file->path));

            $file->opt_fors()->detach();
            $file->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return Response::danger('Wystąpił błąd podczas usuwania pliku. Spróbuj ponownie.', e: $e);
        }

        return Response::success('Plik został usunięty.');
    }
}
