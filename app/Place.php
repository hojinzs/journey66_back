<?php

namespace App;

use App\PlaceTagComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{

    use SoftDeletes;

    protected $attributes = [
        'status' => 'live',
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withPivot('id','tagging_counts')->withTimestamps();
    }

    Public function likes()
    {
        return $this->morphMany('App\Like','like_what');
    }

    Public function owner()
    {
        return $this->hasOne('App\User');
    }

    Public function recommends()
    {
        return $this->hasMany('App\PlaceRecommend')->where(['hidden' => false]);
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

}
