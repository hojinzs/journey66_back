<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Place extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'geoPoint' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'phone' => $this->phone_number,
            'formattedAddress' => $this->address1.' '.$this->address2,
            'Url' => $this->homepage,
            'Image' => $this->thumbnail,
            'tags' => $this->tags,
            'likes_count' => count($this->likes),
            'recommends_count' => count($this->recommends),
        ];
    }
}