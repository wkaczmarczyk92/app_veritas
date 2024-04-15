<?php

namespace App\Services\Compendium;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Response;
use App\Models\Courses\Compendium;

use Spatie\Permission\Models\Role;

class CompendiumStoreService
{
    public function store($request)
    {
        DB::beginTransaction();

        try {
            $compendium = Compendium::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'time_to_read' => $request['time_to_read'],
                'compendium_type_id' => 1,
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
        } catch (\Exception $e) {
            DB::rollback();

            return Response::danger('Dodanie kompenium nie powiodło się. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Nowe kompendium zostało dodane. Za chwilę zostaniesz przeniesiony...', [
            'id' => $compendium->id
        ]);
    }
}
