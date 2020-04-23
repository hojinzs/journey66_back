<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PlaceType;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class PlaceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $placeTypes = PlaceType::query()
            ->withCount('places')
            ->get();

        return response($placeTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        //
        $placeType = new PlaceType();

        $request->validate([
            'name' => ['required','alpha_num','max:20',
                Rule::unique('place_types')],
            'label' => ['required','max:20'],
            'description' => ['required','max:50'],
        ]);

        $placeType = $this->set($request, $placeType);

        return response($placeType);
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
        $placeType = PlaceType::query()->find($id);

        return response($placeType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        //
        $placeType = PlaceType::query()->find($id);

        $request->validate([
            'label' => ['required','max:20'],
            'description' => ['required','max:50'],
        ]);

        $placeType = $this->set($request, $placeType);

        return response($placeType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        //
        $placeType = PlaceType::query()
            ->withCount('places')
            ->find($id);

        if($placeType->places_count > 0){
            return response('이 타입을 사용하는 장소 정보가 있습니다.', 500);
        }

        try {
            $placeType->delete();
        }
        catch (\Exception $exception)
        {
            if(App::environment('local')){
                throw $exception;
            }
            return response('Internal Server Error',500);
        }

        return response('OK',200);
    }

    /**
     * Insert of Update PlaceType and return
     *
     * @param Request $request
     * @param PlaceType $placeType
     * @param string $mode insert|update
     * @return PlaceType|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    private function set(Request $request, PlaceType $placeType, $mode = 'insert')
    {
        try {
            if($mode = 'insert'){
                $placeType->name = $request->input('name');

            }
            $placeType->label = $request->input('label');
            $placeType->description = $request->input('description');
            $placeType->save();
        }
        catch (\Exception $exception)
        {
            if(App::environment('local')){
                throw $exception;
            }
            return response('Internal Server Error',500);
        }

        return $placeType;
    }
}
