<?php

namespace App\Services\Video;

use App\Models\Courses\VideoFramed;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;

class VideoFramedStoreService
{
    public function store($request)
    {
        DB::beginTransaction();

        try {
            $video = VideoFramed::create([
                'name' => $request['name'],
                'vimeo_video_id' => $request['vimeo_video_id']
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Pomyślnie dodano film.', [
            'video' => $video
        ]);
    }
}