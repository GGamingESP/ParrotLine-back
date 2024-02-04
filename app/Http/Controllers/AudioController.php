<?php

namespace App\Http\Controllers;

use App\Http\Requests\AudioStoreRequest;
use App\Http\Requests\AudioUpdateRequest;
use App\Http\Resources\AudioCollection;
use App\Http\Resources\AudioResource;
use App\Models\Audio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AudioController extends Controller
{
    public function index(Request $request): Response
    {
        $audio = Audio::all();

        return new AudioCollection($audio);
    }

    public function store(AudioStoreRequest $request): Response
    {
        $audio = Audio::create($request->validated());

        return new AudioResource($audio);
    }

    public function show(Request $request, Audio $audio): Response
    {
        return new AudioResource($audio);
    }

    public function update(AudioUpdateRequest $request, Audio $audio): Response
    {
        $audio->update($request->validated());

        return new AudioResource($audio);
    }

    public function destroy(Request $request, Audio $audio): Response
    {
        $audio->delete();

        return response()->noContent();
    }
}
