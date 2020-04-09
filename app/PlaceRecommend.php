<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\PlaceRecommendSaved;

class PlaceRecommend extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['comment'];

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

    public function userLike(User $user = null)
    {
        if($user) {
            $like = $this->likes()->where('user_id',$user->id)->first();
            if($like) {
                return true;
            }
        }
        return false;
    }

    protected $dispatchesEvents = [
        'saved' => PlaceRecommendSaved::class,
    ];
}
