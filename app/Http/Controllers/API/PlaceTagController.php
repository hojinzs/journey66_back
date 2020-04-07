<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tag as TagResource;
use App\Place;
use App\PlaceTagComment;
use App\Tag;
use Illuminate\Http\Request;

class PlaceTagController extends Controller
{
    //
    public function index(Place $place)
    {
        return TagResource::collection($place->tags()->get());
    }
}
