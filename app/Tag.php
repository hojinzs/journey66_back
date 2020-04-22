<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tag extends Model
{
    //
    use SoftDeletes;

    protected $attributes = [
        'type' => 'others',
        'icon_prefix' => 'fas',
        'icon_name' => 'tag',
    ];

    public function tagType()
    {
        return $this->belongsTo('App\TagType','type','name');
    }

}
