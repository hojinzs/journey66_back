<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Place;
use App\Tag;
use App\PlaceTagComment;
use Illuminate\Support\Facades\Log;

class PlaceTagCommentCreating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PlaceTagComment $comment)
    {
        //
        $place = $comment->prepare_place;
        $tag = $comment->prepare_tag;

        $place_tag = $place->tags()->wherePivot('tag_id',$tag->id)->first();

        if($place_tag == null){
            $place->tags()->attach($tag);
//            $place_tag_id = $place->tags()->wherePivot('tag_id',$tag->id)->first()->pivot->id;
        } else {
//            $place_tag_id = $place_tag->id;
        }

        $place_tag_id = $place->tags()->wherePivot('tag_id',$tag->id)->first()->pivot->id;

        $comment->place_tag_id = $place_tag_id;
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
