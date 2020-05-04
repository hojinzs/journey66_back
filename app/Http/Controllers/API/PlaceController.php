<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Place as PlaceResource;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;

class PlaceController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:admin')->only(
            'store', 'update'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        //

        $places = Place::query()->withCount(['likes','recommends']) ;

        if($request->query('name')){
            $places->where('name','like','%'.$request->query('name').'%');
        }

        /**
         * 원점(latitude, longitude)과 거리(distance)가 주어 졌을 때, 원점으로부터 거리 안에 있는 장소만 검색
         */
        if($request->query('latitude') && $request->query('longitude') && $request->query('distance')){
            $requestPosition = new Coordinate(array(
                $request->query('latitude'),
                $request->query('longitude')
            ));
            $distance = $request->query('distance') * 1000;
            $geoTools = new Geotools();

            $north = $geoTools->vertex()->setFrom($requestPosition)->destination(0,$distance);
            $east = $geoTools->vertex()->setFrom($requestPosition)->destination(90,$distance);
            $south = $geoTools->vertex()->setFrom($requestPosition)->destination(180,$distance);
            $west = $geoTools->vertex()->setFrom($requestPosition)->destination(270,$distance);

            $places
                ->whereBetween('latitude',array(
                    $south->getLatitude(),
                    $north->getLatitude(),
            ))
                ->whereBetween('longitude',array(
                    $west->getLongitude(),
                    $east->getLongitude(),
            ));

        }

        /**
         * 원점(latitude, longitude)과 거리(distance)가 주어 졌을 때, 원점으로부터 가까운 거리순으로 정렬하고 결과를 출력
         */
        if($request->query('latitude') && $request->query('longitude') && $request->query('distance')){

            $origin = array(
                $request->query('latitude'),
                $request->query('longitude')
            );
            $distance = $request->query('distance');

            $places = $places->limit(100)->get()
                ->each(function (Place $place) use ($origin){
                    $place->setDistanceFromOrigin($origin);
                })
                ->reject(function($place) use($origin, $distance){
                    Log::info('distance => '.$place->distanceFromOrigin);
                    return $place->distanceFromOrigin > $distance;
                })
                ->sortBy('distanceFromOrigin');

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentItems = $places->splice($perPage * ($currentPage - 1),$perPage);
            $placesPaginator = new LengthAwarePaginator($currentItems, count($places), $perPage);

            return PlaceResource::collection($placesPaginator);
        }

        return PlaceResource::collection($places->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return PlaceResource|void
     */
    public function store(Request $request)
    {
        $place = new Place();
        $this->validation($request);

        try {
            $place = $this->set($place,$request);
            $place->save();
        }
        catch (\Exception $exception){
            return abort(500, 'Internal Server Error');
        }

        return new PlaceResource($place);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return PlaceResource
     */
    public function show($id)
    {
        //
        $place = Place::withCount(['likes','recommends'])->find($id);
        return new PlaceResource($place);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return PlaceResource|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     */
    public function update(Request $request, $id)
    {
        //
        $place = Place::find($id);

        $this->validation($request);

        try {
            $place = $this->set($place,$request);
            $place->save();
        }
        catch (\Exception $exception)
        {
            if(App::environment('local')){
                throw $exception;
            }
            return response('Internal Server Error',500);
        }

        return new PlaceResource($place);

    }

//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id)
//    {
//        //
//    }

    /**
     *  validate resource
     *
     * @param Request $request
     * @return array
     */
    protected function validation(Request $request)
    {
        return $request->validate([
            'name' => ['required','max:30'],
            'description' => ['nullable','max:255'],
            'latitude' => ['required','numeric'],
            'longitude' => ['required','numeric'],
            'thumbnail' => ['nullable','url'],
            'type' => ['required','alpha'],
            'zip_code' => ['nullable','digits:5'],
            'address1' => ['nullable','max:50'],
            'address2' => ['nullable','max:50'],
            'phone_number' => ['nullable','max:50'],
            'homepage' => ['nullable','url'],
        ]);
    }

    /**
     * set Place Data
     *
     * @param Place $place
     * @param Request $request
     * @return Place
     */
    protected function set(Place $place, Request $request)
    {
        $place->name = $request->input('name');
        $place->description = $request->input('description');
        $place->latitude = $request->input('latitude');
        $place->longitude = $request->input('longitude');
        $place->thumbnail = $request->input('thumbnail');
        $place->type = $request->input('type');
        $place->zip_code = $request->input('zip_code');
        $place->address1 = $request->input('address1');
        $place->address2 = $request->input('address2');
        $place->phone_number = $request->input('phone_number');
        $place->homepage = $request->input('homepage');

        return $place;
    }


}
