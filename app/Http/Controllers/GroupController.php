<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\HasJoined;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::all();

        return GroupResource::collection($groups);
    }

    public function store(GroupStoreRequest $request)
    {
        $group = Group::create($request->validated());

        $user = auth()->user();

        $newJoined = new HasJoined;

        $newJoined->user_id = $user->id;

        $newJoined->group_id = $group->id;

        $newJoined->isOwner = true;

        $newJoined->save();

        return new GroupResource($group);
    }

    public function show(Request $request, Group $group)
    {
        return new GroupResource($group);
    }

    public function update(GroupUpdateRequest $request, Group $group)
    {
        $group->update($request->validated());

        return new GroupResource($group);
    }

    public function destroy(Request $request, Group $group)
    {
        $group->delete();

        return response()->noContent();
    }

    public function allMessages(Group $group)
    {
        $messages = $group->messages;

        return MessageResource::collection($messages);
    }

    public function allUsers(Group $group)
    {
        $users = $group->users;

        return UserResource::collection($users);
    }

    public function saveGroupImage(Request $request, Group $group)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagen = $request->file('imagen');

        // Generar un nombre único para la imagen
        $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();

        // Guardar la imagen en el sistema de archivos
        Storage::disk('public')->put('imagenes/' . $nombreImagen, $imagen->getContent());

        $newGroupImage = new Image;
        $newGroupImage->url = 'imagenes/' . $nombreImagen;

        $group->image->save($newGroupImage);

        return new ImageResource($group->image);
    }
}
