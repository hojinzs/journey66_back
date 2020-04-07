<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\PlaceTagComment;
use App\Place;
use App\Tag;
use Illuminate\Http\Request;

class PlaceTagCommentController extends Controller
{
    //
    public function index(Request $request, Place $place, Tag $tag)
    {

        $comments = PlaceTagComment::where('place_id',$place->id)
            ->where('tag_id',$tag->id);

        if($request->user()){
            $comments->where('user_id',$request->user()->id);
        }

        return \App\Http\Resources\PlaceTagComment::collection($comments->paginate(5));
    }

    public function owned(Request $request, Place $place, Tag $tag)
    {
        $comments = PlaceTagComment::where('place_id',$place->id)
            ->where('tag_id',$tag->id)
            ->where('user_id',$request->user()->id);

        return \App\Http\Resources\PlaceTagComment::collection($comments->get());
    }
}
