<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaceRecommend extends JsonResource
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
            'comment' => $this->comment,
            'likes' => count($this->likes),
            'user_like' => $this->userLike($request->user('sanctum')),
            'author' => $this->user,
            'written_at' => $this->created_at,
            'place' => $this->place,
            'hidden' => $this->hidden,
        ];
    }
}
