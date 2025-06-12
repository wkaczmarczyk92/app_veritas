<?php

namespace App\Services\Questions;

use App\Helpers\Transaction;
use App\Models\Common\File;
use App\Models\Common\FileMimeType;
use App\Models\Test\Question;
use Illuminate\Support\Facades\Hash;

class UploadAudioService
{
    public function __invoke($request)
    {
        ini_set('max_execution_time', 0); // Bez limitu
        ini_set('max_input_time', 0);     // Bez limitu na dane wejściowe
        ini_set('memory_limit', '-1'); // lub -1 dla braku limitu

        return Transaction::try(
            function () use ($request) {
                $mime_types = FileMimeType::all();
                $file = $request->file('audio_file');
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

                $question = Question::find($request->question_id);
                $question->file()->save($new_file);

                return [
                    'file' => $new_file
                ];
            },
            'Plik audio został dodany.'
        );
    }
}
