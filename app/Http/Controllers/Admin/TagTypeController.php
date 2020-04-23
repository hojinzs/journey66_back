<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\TagType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class TagTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tagTypes = TagType::query()
            ->withCount('tags')
            ->get();

        return response($tagTypes);
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
        $request->validate([
            'name' => ['required','alpha_num','max:20',
                Rule::unique('tag_types')],
            'label' => ['required','max:20'],
            'description' => ['required','max:50'],
        ]);

        $tagType = $this->set($request, new TagType(), 'insert');

        return response($tagType);
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
        $tagType = TagType::query()->find($id);

        return response($tagType);
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
        $tagType = TagType::query()->find($id);

        $request->validate([
            'label' => ['required','max:20'],
            'description' => ['required','max:50'],
        ]);
        $tagType = $this->set($request, $tagType, 'update');

        return response($tagType);
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
        $tagTypes = TagType::query()
            ->withCount('tags')
            ->find($id);

        if($tagTypes->tags_count > 0)
        {
            return response('이 타입을 사용하는 태그 정보가 있습니다.', 500);
        }

        try {
            $tagTypes->delete();
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
     * Insert of Update TagType and return
     *
     * @param Request $request
     * @param TagType $tagType
     * @param string $mode insert|update
     * @return TagType|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    private function set(Request $request, TagType $tagType, $mode = 'insert')
    {
        try {
            if($mode == 'insert'){
                $tagType->name = $request->input('name');
            }
            $tagType->label = $request->input('label');
            $tagType->description = $request->input('description');
            $tagType->save();
        }
        catch (\Exception $exception)
        {
            if(App::environment('local')){
                throw $exception;
            }
            return response('Internal Server Error',500);
        }

        return $tagType;
    }
}
