<?php

namespace App\Services\Lesson;

use App\Models\Courses\Compendium;

use Illuminate\Support\Facades\DB;
use App\Helpers\Response;

use App\Models\Courses\Lesson;

class LessonOrderUpdateService
{
    public function update_order_with_compendium(array $request, Compendium $compendium)
    {
        DB::beginTransaction();

        try {
            foreach ($request as $index => $lesson) {
                $l = Lesson::find($lesson['id']);
                $l->order = $index + 1;
                $l->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Dodanie lekcji nie powiodło się. Spróbuj ponownie później.', e: $e);
        }

        $compendium->load('lessons');

        return Response::success('Kolejność lekcji została zmieniona.', [
            'lessons' => $compendium->lessons
        ]);
    }
}