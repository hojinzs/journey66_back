<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Place;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('auth:admin')->only(
            'index'
        );
    }

    public function index(Request $request)
    {
        $users = User::query();

        if($request->query('name')){
            $users->where('name','like','%'.$request->query('name').'%');
        }
        if($request->query('email')){
            $users->where('email','like','%'.$request->query('email').'%');
        }
        if($perPage = $request->query('per_page') > 0){
            $perPage = $request->query('per_page');
        } else {
            $perPage = 10;
        }

        return UserResource::collection($users->paginate($perPage));
    }

    public function show($id)
    {
        $user = User::find($id);
        return $user;
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
