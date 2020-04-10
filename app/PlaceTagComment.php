<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Events\PlaceTagCommentSaved;

class PlaceTagComment extends Model
{
    protected static function booted()
    {
        static::saved(function(PlaceTagComment $comment) {
            $tag = Tag::find($comment->tag_id);
            $place = Place::find($comment->place_id);

            $place_tag = $place->tags()->wherePivot('tag_id',$tag->id)->first();
            if($place_tag == null){
                $place->tags()->attach($tag,[
                    'last_comment_id' => $comment->id,
                    'tagging_counts' => 1
                ]);
            } else {
                $placeTag = PlaceTag::find($place_tag->pivot->id);
                $placeTag->last_comment_id = $comment->id;
                $placeTag->tagging_counts = ++$placeTag->tagging_counts;
                $placeTag->push();
            }
        });

        static::deleted(function(PlaceTagComment $comment) {
            $place_tag = PlaceTag::where('tag_id',$comment->tag_id)
                ->where('place_id',$comment->place_id)
                ->first();

            if($place_tag->tagging_counts == 1)
            {
                $place_tag->delete();
            } else {
                $placeTag = PlaceTag::find($place_tag->id);
                $placeTag->tagging_counts = --$placeTag->tagging_counts;
                $placeTag->push();
            }
        });
    }

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
        return $this->morphMany('App\Like', 'like_what');
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


//    protected $dispatchesEvents = [
////        'creating' => PlaceTagCommentCreating::class,
//        'saved' => PlaceTagCommentSaved::class,
//    ];
}
