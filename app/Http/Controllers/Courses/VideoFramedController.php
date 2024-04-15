<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

use App\Http\Requests\Video\StoreRequest;
use App\Http\Requests\Video\UpdateRequest;

use App\Services\Video\VideoFramedStoreService;
use App\Services\Video\VideoFramedUpdateService;
use App\Services\Video\VideoFramedDestroyService;

use App\Models\Courses\VideoFramed;


class VideoFramedController extends Controller
{
    public function index()
    {
        return Inertia::render('CourseModerator/Video/Index');
    }

    public function store(StoreRequest $request)
    {
        return (new VideoFramedStoreService)->store($request->all());
    }

    public function destroy(VideoFramed $video)
    {
        return (new VideoFramedDestroyService)->destroy($video);
    }

    public function list(Request $request)
    {
        return response()->json([
            'videos' => VideoFramed::paginate(20)
        ]);
    }

    public function update(UpdateRequest $request, int $id)
    {
        return (new VideoFramedUpdateService)->update($request->all(), VideoFramed::find($id));
    }
}
