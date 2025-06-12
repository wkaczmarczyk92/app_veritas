<?php

namespace App\Services\GermanLessons;

use App\Helpers\Transaction;
use App\Models\Common\FileMimeType;
use App\Models\Courses\GermanLesson;
use Illuminate\Support\Facades\Hash;
use App\Models\Common\File;
use App\Models\Courses\Lesson;
use Illuminate\Support\Facades\Auth;

class GermanLessonStoreService
{
    public function __invoke($request)
    {
        // dd($request->order);
        ini_set('max_execution_time', 0); // Bez limitu
        ini_set('max_input_time', 0);     // Bez limitu na dane wejściowe
        ini_set('memory_limit', '-1'); // lub -1 dla braku limitu

        return Transaction::try(
            function () use ($request) {
                $mime_types = FileMimeType::all();
                $german_lesson = GermanLesson::find(1);

                $lesson = new Lesson([
                    'name' => $request['name'],
                    'description' => null,
                    'time_to_read' => null,
                    'visibility_id' => 1,
                    'created_by' => Auth::id(),
                    'order' => $request->order
                ]);

                $german_lesson->lessons()->save($lesson);

                if ($request->file('lesson')) {
                    $file = $request->file('lesson');
                    $file_extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('', $filename, 'lessons');

                    do {
                        $hash = Hash::make($file->getClientOriginalName() . time());
                        $hash = '[' . substr($hash, 0, 50) . ']';
                    } while (File::where('hash', $hash)->exists());

                    $new_file = File::create([
                        'name' => $filename,
                        'path' => $path,
                        'extension' => $file_extension,
                        'size' => $file->getSize(),
                        'file_mime_type_id' => $this->mime_types_map[$file->getMimeType()] ?? null,
                        'file_type_id' => 3,
                        'hash' => $hash
                    ]);

                    $lesson->files()->save($new_file);
                }
            },
            'Plik z lekcją został wrzucony.'
        );
    }
}
