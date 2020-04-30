<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Place as PlaceResource;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        $place = Place::query()->withCount(['likes','recommends']) ;

        if($request->query('name')){
            $place->where('name','like','%'.$request->query('name').'%');
        }

        return PlaceResource::collection($place->paginate(10));
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
