<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'group_id' => $this->group_id,
            'text' => $this->text,
            'imagen' => new MessageResource($this->image),
            'image' => ImageResource::make($this->whenLoaded('image')),
            'audio' => AudioResource::make($this->whenLoaded('audio')),
        ];
    }
}
