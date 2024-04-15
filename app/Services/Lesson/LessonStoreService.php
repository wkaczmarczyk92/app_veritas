<?php

namespace App\Services\Lesson;

use App\Models\Courses\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Response;

class LessonStoreService
{
    public function store_with_compendium($request, $compendium)
    {
        DB::beginTransaction();

        try {
            $lesson = new Lesson([
                'name' => $request['name'],
                'description' => $request['description'],
                'time_to_read' => $request['time_to_read'],
                'visibility_id' => $request['visibility_id'],
                'created_by' => Auth::id(),
            ]);

            $compendium->lessons()->save($lesson);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return Response::danger('Dodanie lekcji nie powiodło się. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Nowa lekcja została dodana. Za chwilę zostaniesz przeniesiony...', [
            'id' => $compendium->id
        ]);
    }
}
