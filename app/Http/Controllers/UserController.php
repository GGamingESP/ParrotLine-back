<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\UserResource;
use App\Models\Friend;
use App\Models\HasJoined;
use App\Models\Group;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $users = User::all();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function allGroups(Request $request)
    {
        $user = auth()->user();


        $grupos = $user->groups;

        return GroupResource::collection($grupos);
    }

    public function userImage(User $user)
    {
        $image = $user->image;

        return new ImageResource($image);
    }

    public function friendsAndRequests()
    {
        $user = auth()->user();

        $friends = $user->friends;

        $response = [
            "status" => 'success',
            'data' => $friends
        ];

        return response()->json($response, 200);
    }

    public function sendFriendRequest(User $user)
    {
        $ownUser = auth()->user();
        $newFriend = new Friend;

        $newFriend->user_id = $ownUser->id;
        $newFriend->friend_id = $user->id;
        $newFriend->accepted = false;
        $newFriend->blocked = false;
        $ownUser->friends()->save($newFriend); // funciona aunque de error

        $ownFriend = new Friend;

        $ownFriend->user_id = $user->id;
        $ownFriend->friend_id = $ownUser->id;
        $ownFriend->accepted = false;
        $ownFriend->blocked = false;
        $user->friends()->save($ownFriend);

        $response = [
            "data" => "success"
        ];

        return response()->json($response, 200);
    }

    public function acceptRequest(User $user)
    {
        $ownUser = auth()->user();

        $ownFriend = $ownUser->friends(); // funciona
        $ownFriend->where("friend_id", $user->id)->where("user_id", $ownUser->id)->update(['accepted' => 1]);

        $otherFriends = $user->friends(); // funciona
        $otherFriends->where("friend_id", $ownUser->id)->where("user_id", $user->id)->update(['accepted' => 1]);

        $response = [
            "data" => "success"
        ];

        return response()->json($response, 200);
    }

    public function saveUserImage(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagen = $request->file('imagen');

        // Generar un nombre único para la imagen
        $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();

        // Guardar la imagen en el sistema de archivos
        Storage::disk('public')->put('imagenes/' . $nombreImagen, $imagen->getContent());

        $newUserImage = new Image;
        $newUserImage->url = 'imagenes/' . $nombreImagen;

        $user = auth()->user();

        $user->image->save($newUserImage);

        return new ImageResource($user->image);
    }

    public function leaveGroup(Group $group)
    {
        $user = auth()->user();

        $relation = $user->joined();

        $relation->where("group_id", $group->id)->delete();

        $response = [
            "data" => "success"
        ];

        return response()->json($response, 200);
    }
}
