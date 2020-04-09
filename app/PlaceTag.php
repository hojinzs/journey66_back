<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaceTag extends Pivot
{
    //
    public $incrementing = true;

//    public function comments()
//    {
//        $this->hasMany('App\PlaceTagComment');
//    }

    public function place()
    {
        $this->belongsTo('App\Place');
    }

    public function tag()
    {
        $this->belongsTo('App\Tag');
    }

//    public function tagging()
//    {
//        $this->loadCount($this->tag());
//    }

}
