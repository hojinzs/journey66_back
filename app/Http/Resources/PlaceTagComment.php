<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaceTagComment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->content,
            'likes' => count($this->likes),
            'author' => $this->user,
            'written_at' => $this->created_at,
            'place' => $this->place,
            'tag' => $this->tag,
        ];
    }
}
