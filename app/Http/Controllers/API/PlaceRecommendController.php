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
        return PlaceRecommendResource::collection(
                $recommends->paginate(5)
        );
    }

    public function store(Request $request,Place $place)
    {
        $placeRecommend = new PlaceRecommend([
            'comment' => $request->input('content')
        ]);
        $placeRecommend->user()->associate($request->user());

        $place->recommends()->save($placeRecommend);

        return new PlaceRecommendResource($placeRecommend);
    }

    public function destroy(Request $request, $place, PlaceRecommend $recommend)
    {
        try
        {
            if($recommend->user_id != $request->user()->id) abort(403);
            if($recommend->place_id != $place) abort(404);

            $recommend->delete();
            return response('PlaceRecommend destroy success',200);
        }
        catch (\Exception $exception)
        {
            return response($exception, 400);
        }
    }

    public function owned(Request $request, Place $place)
    {
        $recommends = PlaceRecommend::where('place_id',$place->id);
        $recommends->where('user_id',$request->user()->id);
        return PlaceRecommendResource::collection(
            $recommends->get()
        );

    }

    public function pinned(Place $place)
    {
        $recommends = $place->recommends()->withCount('likes')->orderBy('likes_count','desc')->first();
        return new PlaceRecommendResource($recommends);
    }


}
