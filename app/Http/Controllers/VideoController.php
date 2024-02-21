<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoStoreRequest;
use App\Http\Requests\VideoUpdateRequest;
use App\Http\Resources\VideoCollection;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $videos = Video::all();

        return new VideoCollection($videos);
    }

    public function store(VideoStoreRequest $request)
    {
        $video = Video::create($request->validated());

        return new VideoResource($video);
    }

    public function show(Request $request, Video $video)
    {
        return new VideoResource($video);
    }

    public function update(VideoUpdateRequest $request, Video $video)
    {
        $video->update($request->validated());

        return new VideoResource($video);
    }

    public function destroy(Request $request, Video $video): Response
    {
        $video->delete();

        return response()->noContent();
    }
}
