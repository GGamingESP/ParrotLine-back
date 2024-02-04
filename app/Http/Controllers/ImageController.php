<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageStoreRequest;
use App\Http\Requests\ImageUpdateRequest;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $images = Image::all();

        return new ImageCollection($images);
    }

    public function store(ImageStoreRequest $request)
    {
        // aqui no se guardan las imagenes
    }

    public function show(Request $request, Image $image)
    {
        return new ImageResource($image);
    }

    public function update(ImageUpdateRequest $request, Image $image)
    {
        $image->update($request->validated());

        return new ImageResource($image);
    }

    public function destroy(Request $request, Image $image)
    {
        $image->delete();

        return response()->noContent();
    }
}
