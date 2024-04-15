<?php

namespace App\Services\Compendium;

use Illuminate\Support\Facades\DB;
use App\Models\Courses\Compendium;

use App\Helpers\Response;

class VademecumUpdateService
{
    public function update($request, $id) {

        DB::beginTransaction();

        try {
            $compendium = Compendium::find($id);
            $compendium->name = $request['name'];
            $compendium->description = $request['description'];
            $compendium->time_to_read = $request['time_to_read'];
            $compendium->save();

            DB::commit();
        } catch (\Exception $th) {
            DB::rollback();
            return Response::danger('Wystąpił błąd podczas aktualizacji vademecum. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Vademecum zostało zaktualizowane. Zostaniesz przeniesiony...');
    }
}
