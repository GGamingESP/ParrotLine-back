<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(LoginRegisterController::class)->group(function () {
    Route::post('/register', 'register'); // funcional
    Route::post('/login', 'login'); // funcional
    Route::delete('/logout', 'logout'); // no funcional
});

Route::middleware('auth:sanctum')->group(function () {
    // peticiones de cada cosa
    Route::apiResource('message', App\Http\Controllers\MessageController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Message not found'], 404);
    });;

    Route::apiResource('group', App\Http\Controllers\GroupController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Group not found'], 404);
    });;

    Route::apiResource('image', App\Http\Controllers\ImageController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Image not found'], 404);
    });;

    Route::apiResource('video', App\Http\Controllers\VideoController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Video not found'], 404);
    });;

    Route::apiResource('audio', App\Http\Controllers\AudioController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Audio not found'], 404);
    });;

    Route::apiResource('user', App\Http\Controllers\UserController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'User not found'], 404);
    });

    Route::get("/allGroups", [UserController::class, 'allGroups']); // todos los grupos a los que pertenece el usuario
    Route::get("/groupmessages/{group}", [GroupController::class, 'allMessages']); // todos los mensajes de un grupo
    Route::get("/groupusers/{group}", [GroupController::class, 'allUsers']); // todos los usuarios de un grupo
    Route::get("/friendsAndRequest", [UserController::class, 'friendsAndRequests']); // todos las amigos y solicitudes de amistad de un usuario
    Route::get("/getImage/{user}", [UserController::class, 'userImage']); // imagen del usuario que envies
    Route::get("/sendRequest/{user}", [UserController::class, 'sendFriendRequest']); // envia la solicitud de amistad
    Route::get("/acceptRequest/{user}", [UserController::class, 'acceptRequest']); // acepta la solicitud de un usuario
    Route::get("/leaveGroup/{group}", [UserController::class, 'leaveGroup']); // te sales de un grupo
    Route::post("/saveUserImage", [UserController::class, 'saveUserImage']); // ruta para guardar imagen del usuario
    Route::post("/saveGroupImage/{group}", [GroupController::class, 'saveGroupImage']); // ruta para guardar imagen del grupo
    Route::post("/createMessageWithImage", [MessageController::class, 'createMessageWithImage']); // ruta para guardar mensaje con imagen
    Route::post("/createMessageWithVideo", [MessageController::class, 'createMessageWithVideo']); // ruta para guardar mensaje con video
    Route::post("/createMessageWithAudio", [MessageController::class, 'createMessageWithAudio']); // ruta para guardar mensaje con audio
});

Route::prefix('desktop')->middleware('auth:sanctum')->middleware('handleCorsDesktop')->group(function () {
    Route::apiResource('message', App\Http\Controllers\MessageController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Message not found'], 404);
    });;

    Route::apiResource('group', App\Http\Controllers\GroupController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Group not found'], 404);
    });;

    Route::apiResource('image', App\Http\Controllers\ImageController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Image not found'], 404);
    });;

    Route::apiResource('video', App\Http\Controllers\VideoController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Video not found'], 404);
    });;

    Route::apiResource('audio', App\Http\Controllers\AudioController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'Audio not found'], 404);
    });;

    Route::apiResource('user', App\Http\Controllers\UserController::class)->missing(function (Request $request) {
        return response()->json(['error' => 'User not found'], 404);
    });

    Route::get("/allGroups", [UserController::class, 'allGroups']); // todos los grupos a los que pertenece el usuario
    Route::get("/groupmessages/{group}", [GroupController::class, 'allMessages']); // todos los mensajes de un grupo
    Route::get("/groupusers/{group}", [GroupController::class, 'allUsers']); // todos los usuarios de un grupo
    Route::get("/friendsAndRequest", [UserController::class, 'friendsAndRequests']); // todos las amigos y solicitudes de amistad de un usuario
    Route::get("/getImage/{user}", [UserController::class, 'userImage']); // imagen del usuario que envies
    Route::get("/sendRequest/{user}", [UserController::class, 'sendFriendRequest']); // envia la solicitud de amistad
    Route::get("/acceptRequest/{user}", [UserController::class, 'acceptRequest']); // acepta la solicitud de un usuario
    Route::get("/leaveGroup/{group}", [UserController::class, 'leaveGroup']); // te sales de un grupo
    Route::post("/saveUserImage", [UserController::class, 'saveUserImage']); // ruta para guardar imagen del usuario
    Route::post("/saveGroupImage/{group}", [GroupController::class, 'saveGroupImage']); // ruta para guardar imagen del grupo
    Route::post("/createMessageWithImage", [MessageController::class, 'createMessageWithImage']); // ruta para guardar mensaje con imagen
    Route::post("/createMessageWithVideo", [MessageController::class, 'createMessageWithVideo']); // ruta para guardar mensaje con video
    Route::post("/createMessageWithAudio", [MessageController::class, 'createMessageWithAudio']); // ruta para guardar mensaje con audio
});
