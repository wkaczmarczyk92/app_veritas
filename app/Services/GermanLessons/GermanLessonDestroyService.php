<?php

namespace App\Services\GermanLessons;

use App\Helpers\Transaction;
use Illuminate\Support\Facades\Storage;
use App\Models\Courses\Lesson;

class GermanLessonDestroyService
{
    public function __invoke($id)
    {
        return Transaction::try(
            function () use ($id) {
                $lesson = Lesson::with('files')->find($id);

                Storage::disk('lessons')->delete($lesson->files[0]->path);
                $lesson->files[0]->delete();

                $lesson->delete();
            },
            'Lekcja została usunięta.'
        );
    }
}
