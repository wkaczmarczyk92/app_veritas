<?php

namespace App\Services\Questions;

use App\Helpers\Transaction;
use App\Models\Common\File;

class DestroyAudioService
{
    public function __invoke($file_id)
    {
        return Transaction::try(
            function () use ($file_id) {
                $file = File::find($file_id);
                $file_path = $file->path;
                $file->delete();

                if (file_exists(public_path("lessons/{$file_path}"))) {
                    unlink(public_path("lessons/{$file_path}"));
                }
            },
            'Plik audio został usunięty.'
        );
    }
}
