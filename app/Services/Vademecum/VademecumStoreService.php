<?php

namespace App\Services\Vademecum;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Response;
use App\Models\Courses\Compendium;

class VademecumStoreService
{
    public function store($request)
    {
        // dd($request['description']);
        DB::beginTransaction();

        try {
            $compendium = Compendium::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'time_to_read' => $request['time_to_read'],
                'compendium_type_id' => 2,
                'visibility_id' => $request['visibility_id'],
                'created_by' => Auth::id(),
            ]);

            if (!empty($request['permissions']['roles'])) {
                $compendium->roles()->attach($request['permissions']['roles']);
            }

            if (!empty($request['permissions']['company_branches'])) {
                $compendium->company_branches()->attach($request['permissions']['company_branches']);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            return Response::danger('Dodanie vademecum nie powiodło się. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Nowe vademecum zostało dodane. Za chwilę zostaniesz przeniesiony...', [
            'id' => $compendium->id
        ]);
    }
}
