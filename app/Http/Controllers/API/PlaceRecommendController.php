<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlaceRecommend as PlaceRecommendResource;
use App\Place;
use App\PlaceRecommend;
use Illuminate\Http\Request;

class PlaceRecommendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store','destroy']);
    }

    public function index(Place $place)
    {
        $recommends = PlaceRecommend::where('place_id',$place->id);
        $recommends->where('hidden',false);
        return response(
            PlaceRecommendResource::collection(
                $recommends->paginate(5)
            )
        );
    }

    public function store(Request $request,Place $place)
    {
        $placeRecommend = new PlaceRecommend([
            'comment' => $request->input('content')
        ]);
        $placeRecommend->user()->associate($request->user());

        $place->recommends()->save($placeRecommend);

        return response($placeRecommend,201);
    }

    public function destroy(Request $request, Place $place, PlaceRecommend $recommend)
    {
        if($recommend->user_id != $request->user()->id) abort(403);
        if($recommend->place_id != $place->id) abort(406);

        $recommend->delete();
        return response('PlaceRecommend destroy success',200);
    }

    public function owned(Request $request, Place $place)
    {
        $recommends = PlaceRecommend::where('place_id',$place->id);
        $recommends->where('user_id',$request->user()->id);
        return response(
            PlaceRecommendResource::collection(
                $recommends->get()
            )
        );
    }


}
