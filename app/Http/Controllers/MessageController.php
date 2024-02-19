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

    public function createMessageWithImage(Request $request)
    {
        $req = array("user_id" => $request->user_id, "group_id" => $request->group_id, "text" => " ");
        $newMessage = Message::create($req);

        $path = Storage::putFile('imagenes', $request->file('imagen'));

        $newMessageImage = new Image;
        $newMessageImage->url = $path;

        $newMessage->image->save($newMessageImage);

        return new MessageResource($newMessage);
    }

    public function createMessageWithVideo(Request $request)
    {
        $message = Message::create($request);

        $imagen = $request->file('imagen');

        // Generar un nombre único para la imagen
        $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();

        // Guardar la imagen en el sistema de archivos
        Storage::disk('public')->put('imagenes/' . $nombreImagen, $imagen->getContent());

        $newMessageVideo = new Video;
        $newMessageVideo->url = 'imagenes/' . $nombreImagen;
        $message->video->save($newMessageVideo);

        return new MessageResource($message);
    }

    public function createMessageWithAudio(Request $request)
    {
        $message = Message::create($request);

        $imagen = $request->file('imagen');

        // Generar un nombre único para la imagen
        $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();

        // Guardar la imagen en el sistema de archivos
        Storage::disk('public')->put('imagenes/' . $nombreImagen, $imagen->getContent());

        $newMessageVideo = new Audio;
        $newMessageVideo->url = 'imagenes/' . $nombreImagen;
        $message->audio->save($newMessageVideo);

        return new MessageResource($message);
    }
}
