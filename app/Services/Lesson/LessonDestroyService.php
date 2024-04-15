<?php

namespace App\Services\Lesson;

use App\Models\Courses\Compendium;

use Illuminate\Support\Facades\DB;
use App\Helpers\Response;

use App\Models\Courses\Lesson;

class LessonDestroyService
{
    public function destroy(Lesson $lesson)
    {
        DB::beginTransaction();

        try {
            $lesson->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Usunięcie lekcji nie powiodło się. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Lekcja została usunięta. Za chwilę zostaniesz przeniesiony...');
    }
}