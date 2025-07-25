<?php

namespace App\Services\GermanLessons;

use App\Models\Courses\Lesson;
use App\Helpers\Transaction;

class GermanLessonVisibilityUpdateService
{
    public function __invoke(int $lesson_id, int $visibility_id)
    {
        return Transaction::try(
            function () use ($lesson_id, $visibility_id) {
                $lesson = Lesson::findOrFail($lesson_id);
                $lesson->visibility_id = $visibility_id;
                $lesson->save();

                $lesson->load('visibility');

                return [
                    'visibility' => $lesson->visibility,
                ];
            },
            'Widoczność lekcji została zaktualizowana.',
            'Lekcja nie została znaleziona.'
        );
    }
}
