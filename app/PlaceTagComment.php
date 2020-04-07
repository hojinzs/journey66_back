<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Events\PlaceTagCommentSaved;

class PlaceTagComment extends Model
{
    //
//    public $prepare_place;
//    public $prepare_tag;

    public function place()
    {
        return $this->belongsTo('App\Place');
    }

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function likes()
    {
        return $this->morphMany('App\Like','like_what');
    }


    protected $dispatchesEvents = [
//        'creating' => PlaceTagCommentCreating::class,
        'saved' => PlaceTagCommentSaved::class,
    ];
}
