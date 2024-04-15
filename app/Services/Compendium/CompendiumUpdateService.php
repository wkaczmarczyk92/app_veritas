<?php

namespace App\Services\Compendium;

use Illuminate\Support\Facades\DB;
use App\Models\Courses\Compendium;

use App\Helpers\Response;

class CompendiumUpdateService
{
    public function update($request, $id)
    {
        // dd($request);
        DB::beginTransaction();

        try {
            $compendium = Compendium::find($id);
            $compendium->name = $request['name'];
            $compendium->description = $request['description'];
            $compendium->time_to_read = $request['time_to_read'];
            $compendium->visibility_id = $request['visibility_id'];
            $compendium->save();

            $compendium->roles()->sync($request['permissions']['roles']);
            $compendium->company_branches()->sync($request['permissions']['company_branches']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Wystąpił błąd podczas aktualizacji kompendium. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Kompendium zostało zaktualizowane. Zostaniesz przeniesiony...');
    }
}
