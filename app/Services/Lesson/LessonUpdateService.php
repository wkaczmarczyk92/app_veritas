<?php

namespace App\Services\Lesson;

use App\Helpers\Response;

use Illuminate\Support\Facades\DB;

class LessonUpdateService
{
    public function update_with_compendium($request, $lesson)
    {
        DB::beginTransaction();

        try {
            $lesson->update([
                'name' => $request['name'],
                'description' => $request['description'],
                'time_to_read' => $request['time_to_read'],
                'visibility_id' => $request['visibility_id'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return Response::danger('Edycja lekcji nie powiodła się. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Lekcja została zaktualizowana. Za chwilę zostaniesz przeniesiony...');
    }
}
