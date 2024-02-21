<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Audio;
use App\Models\Message;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::all();

        return new MessageCollection($messages);
    }

    public function store(MessageStoreRequest $request)
    {
        $message = Message::create($request->validated());

        return new MessageResource($message);
    }

    public function show(Request $request, Message $message)
    {
        return new MessageResource($message);
    }

    public function update(MessageUpdateRequest $request, Message $message)
    {
        $message->update($request->validated());

        return new MessageResource($message);
    }

    public function destroy(Request $request, Message $message)
    {
        $message->delete();

        return response()->noContent();
    }

    public function createMessageWithImage(Request $request, Message $message)
    {
        $path = $request->file('imagen')->store('public/imagenes');

        $newMessageImage = new Image(['url' => $path]);

        $message->image()->save($newMessageImage);

        return new MessageResource($message);
    }

    public function createMessageWithVideo(Request $request, Message $message)
    {
        $path = $request->file('imagen')->store('public/imagenes');

        $newMessageVideo = new Video(['url' => $path]);;
        $message->video()->save($newMessageVideo);

        return new MessageResource($message);
    }

    public function createMessageWithAudio(Request $request, Message $message)
    {
        $path = $request->file('imagen')->store('public/imagenes');

        $newMessageVideo = new Audio(['url' => $path]);
        $message->audio()->save($newMessageVideo);

        return new MessageResource($message);
    }
}
