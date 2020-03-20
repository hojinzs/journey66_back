<?php

namespace App\Events;

use App\PlaceTagComment;
use App\PlaceTag;
use App\Place;
use App\Tag;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlaceTagCommentSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PlaceTagComment $comment)
    {
        $this->getFirstOrCreatePlaceTag($comment);
    }

    /**
     * Get a exist TagPlace Relation Model or Create One
     *
     * @param PlaceTagComment $comment
     * @return mixed
     */
    private function getFirstOrCreatePlaceTag(PlaceTagComment $comment)
    {

        $tag = Tag::find($comment->tag_id);
        $place = Place::find($comment->place_id);

        $place_tag = $place->tags()->wherePivot('tag_id',$tag->id)->first();
        if($place_tag == null){
            $place->tags()->attach($tag,[
                'last_comment_id' => $comment->id,
                'tagging_counts' => 1
            ]);
        } else {
            $place_tag_id = $place->tags()->wherePivot('tag_id',$tag->id)->first()->pivot->id;

            $placeTag = PlaceTag::find($place_tag_id);
            $placeTag->last_comment_id = $comment->id;
            $placeTag->tagging_counts = ++$placeTag->tagging_counts;
            $placeTag->push();

        }


    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
