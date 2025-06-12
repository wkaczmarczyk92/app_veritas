<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Questions\UploadAudioService;
use App\Services\Questions\DestroyAudioService;

class QuestionController extends Controller
{
    public function upload_audio(Request $request)
    {
        return (new UploadAudioService)($request);
    }

    public function destroy_audio(int $file_id)
    {
        return (new DestroyAudioService)($file_id);
    }
}
