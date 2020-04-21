<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use App\Http\Resources\Tag as TagCollection;
use App\Tag;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        //
        $tags = Tag::query();

        if($request->query('name')){
            $tags->where('name','like','%'.$request->query('name').'%');
        }

        return TagCollection::collection($tags->get());
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
        $tag = new Tag();
        $this->validation($request, $tag);

        try {
            $tag = $this->set($tag, $request);
            $tag->save();
        }
        catch (\Exception $exception){
            return abort(500, 'Internal Server Error');
        }
        return $tag;
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
        $tag = Tag::find($id);

        return $tag;
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
        $tag = Tag::find($id);

        $this->validation($request, $tag->id);

        try {
            $tag = $this->set($tag,$request);
            $tag->save();
        }
        catch (\Exception $exception)
        {
            return abort(500,'Internal Server Error');
        }

        return $tag;
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

    protected function validation(Request $request, $tag = null)
    {
        return $request->validate([
            'name' => ['required','alpha','max:30',
                Rule::unique('tags')->ignore($tag)
                ],
            'label' => ['required', 'max:50'],
            'description' => ['required', 'max:255'],
            'icon_prefix' => ['required', 'max:50'],
            'icon_name' => ['required', 'max:50'],
            'color' => ['required', 'max:50'],
            'type' => ['required','alpha']
        ]);
    }

    protected function set(Tag $tag, Request $request)
    {
        $tag->name = $request->input('name');
        $tag->label = $request->input('label');
        $tag->description = $request->input('description');
        $tag->icon_prefix = $request->input('icon_prefix');
        $tag->icon_name = $request->input('icon_name');
        $tag->color = $request->input('color');
        $tag->type = $request->input('type');

        return $tag;
    }
}
