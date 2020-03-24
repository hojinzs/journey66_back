<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\PlaceRecommendSaved;

class PlaceRecommend extends Model
{
    //
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function place()
    {
        return $this->belongsTo('App\Place');
    }

    Public function likes()
    {
        return $this->morphMany('App\Like','like_what');
    }

    protected $dispatchesEvents = [
        'saved' => PlaceRecommendSaved::class,
    ];
}
