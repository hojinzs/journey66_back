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

            $user = Auth::user();
            $response = [
                'user' => $user,
                'api_token' => $user->api_token,
                'provider' => [
                    'strava' => $user->providerTokens()->where('provider','strava')->first(),
                ]
            ];
            return response($response,200);
        }
    }
}
