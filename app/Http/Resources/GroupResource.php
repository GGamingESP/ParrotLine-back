<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => ImageResource::make($this->whenLoaded('image')),
            'messages' => MessageCollection::make($this->whenLoaded('messages')),
            'users' => UserResource::collection($this->users)
        ];
    }
}
