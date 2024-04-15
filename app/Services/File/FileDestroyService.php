<?php

namespace App\Services\File;

use App\Models\Common\File;
use Illuminate\Support\Facades\DB;

use App\Helpers\Response;

class FileDestroyService
{
    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();
            $file = File::find($id);

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
