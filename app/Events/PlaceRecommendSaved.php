<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\PlaceRecommend;

class PlaceRecommendSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PlaceRecommend $placeRecommend)
    {
        //
//        $user = $placeRecommend->user_id;
//        $user->has('place_recommends')->where('hidden',false)->update('hidden',false);

        PlaceRecommend::where('id','!=',$placeRecommend->id)
            ->where('user_id',$placeRecommend->user_id)
            ->where('place_id',$placeRecommend->place_id)
            ->where('hidden',false)
            ->update(['hidden' => true]);
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
