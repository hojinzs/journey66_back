<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\PlaceTagComment;
use App\Place;
use App\Tag;
use Illuminate\Http\Request;

class PlaceTagCommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store','destroy']);
    }

    public function index(Place $place, Tag $tag)
    {

        $comments = PlaceTagComment::where('place_id',$place->id)
            ->where('tag_id',$tag->id);

        return \App\Http\Resources\PlaceTagComment::collection($comments->paginate(5));
    }

    public function store(Request $request, Place $place, Tag $tag)
    {
        $comment = new PlaceTagComment;
        $comment->content = $request->input('content');
        $comment->place()->associate($place);
        $comment->tag()->associate($tag);
        $comment->user()->associate($request->user());
        $comment->save();

        return response(
            new \App\Http\Resources\PlaceTagComment($comment)
        ,201);
    }

    public function destroy(Request $request, $place, $tag, PlaceTagComment $comment)
    {
        try {
            if($comment->user_id != $request->user()->id) abort(403);
            if($comment->place_id != $place) abort(404);
            if($comment->tag_id != $tag) abort(404);

            $comment->delete();

            return response('PlaceTagComment detroy success',200);
        }
        catch (\Exception $exception)
        {
            return response(400);
        }
    }

    public function owned(Request $request, Place $place, Tag $tag)
    {
        $comments = PlaceTagComment::where('place_id',$place->id)
            ->where('tag_id',$tag->id)
            ->where('user_id',$request->user()->id);

        return \App\Http\Resources\PlaceTagComment::collection($comments->get());
    }
}
