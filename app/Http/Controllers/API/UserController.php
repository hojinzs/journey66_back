<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function whoami(Request $request)
    {
        if(Auth::check()){
            $response = [
                'user' => $request->user(),
                'api_token' => $request->user()->api_token,
                'strava' => $request->user()->getProviderToken('strava'),
            ];
            return response($response,200);
        }
    }
}
