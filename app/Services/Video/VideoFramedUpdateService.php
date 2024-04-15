<?php

namespace App\Services\Video;

use Illuminate\Support\Facades\DB;
use App\Helpers\Response;

class VideoFramedUpdateService
{
    public function update($request, $video)
    {
        try {
            $video->update([
                'name' => $request['name'],
                'vimeo_video_id' => $request['vimeo_video_id'],
            ]);
        } catch (\Exception $e) {
            return Response::danger('Aktualizacja wideo nie powiodła się. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Pomyślnie zaktualizowano wideo.', [
            'video' => $video
        ]);
    }
}
