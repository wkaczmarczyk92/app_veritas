<?php

namespace App\Services\Video;

use Illuminate\Support\Facades\DB;
use App\Helpers\Response;

class VideoFramedDestroyService
{
    public function destroy($video) {
        try {
            $video->delete();
        } catch (\Exception $e) {
            Response::danger('Coś poszło nie tak. Spróbuj ponownie później.', e: $e);
        }
        
        return Response::success('Pomyślnie usunięto film.');
    }
}