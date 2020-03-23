<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Place as PlaceResource;
use App\Http\Resources\PlaceRecommend as PlaceRecommendResource;
use App\Place;
use App\PlaceRecommend;
use App\PlaceTagComment;
use Illuminate\Http\Request;
use \App\Http\Resources\Tag as TagResource;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return PlaceResource::collection(Place::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return new PlaceResource(Place::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTags($id)
    {
        return TagResource::collection(Place::find($id)->tags()->get());
    }

    public function getTagsComments($id,$tagId)
    {
        return \App\Http\Resources\PlaceTagComment::collection(
            PlaceTagComment::where('place_id',$id)
                ->where('tag_id',$tagId)
                ->paginate(5)
        );
//        return PlaceTagComment::where('place_id',$id)
//            ->where('tag_id',$tagId)
//            ->paginate(5);
    }

    public function getRecommends($id)
    {
        return PlaceRecommendResource::collection(PlaceRecommend::where('place_id',$id)->paginate(5));
    }

}
