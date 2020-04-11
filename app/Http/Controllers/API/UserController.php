<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Place;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function whoami(Request $request)
    {
        $user = $request->user();

        $response = [
            'user' => $user,
            'api_token' => $user->api_token,
            'provider' => [
                'strava' => $user->providerTokens()->where('provider','strava')->first(),
            ]
        ];
        return response($response,200);
    }

    public function placeByLikes(Request $request)
    {
        $user = $request->user();
        $places = Place::whereHas('likes', function(Builder $query) use ($user) {
            $query->where('user_id',$user->id);
        })->get();
        return response($places);
    }
}
