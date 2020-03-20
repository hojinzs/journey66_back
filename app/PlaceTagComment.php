<?php

namespace App;


use App\Events\PlaceTagCommentCreating;
use App\Events\PlaceTagCommentSaved;
use Illuminate\Database\Eloquent\Model;
use App\Place;
use App\Tag;

class PlaceTagComment extends Model
{
    //
    public $prepare_place;
    public $prepare_tag;

    public function place()
    {
        return $this->hasOneThrough('App\Place','App\PlaceTag');
    }

    public function tag()
    {
        return $this->hasOneThrough('App\Tag','App\PlaceTag');
    }

    public function user()
    {
        return $this->belongsTo('App\user');
    }

    public function placeTag()
    {
        return $this->belongsTo('App\PlaceTag');
    }

    public function preparePlace(Place $place)
    {
        $this->prepare_place = $place;
    }

    public function prepareTag(Tag $tag)
    {
        $this->prepare_tag = $tag;
    }

    protected $dispatchesEvents = [
        'creating' => PlaceTagCommentCreating::class,
        'saved' => PlaceTagCommentSaved::class,
    ];
}
