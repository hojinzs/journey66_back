<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{

    use SoftDeletes;

    protected $attributes = [
        'status' => 'live',
    ];

    Public function owner()
    {
        return $this->hasOne('App/User');
    }

}
