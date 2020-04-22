<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Tag extends JsonResource
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
            'type' => $this->tagType->label,
            'tag_Type' => $this->tagType,
            'label' => $this->label,
            'icon_prefix' => $this->icon_prefox,
            'icon_name' => $this->icon_name,
            'color' => $this->color,
        ];
    }
}
