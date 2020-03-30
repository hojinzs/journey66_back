<?php

namespace App\Http\Controllers;

use App\ProviderToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class StravaController extends Controller
{
    //
    public function redirectTo(Request $request){

        Auth::guard('web')->login($request->user());
        $request->session()->put('returnUrl',$request->query('return_url'));
        return Socialite::with('strava')->redirect();
    }

    public function callback(Request $request) {

        $user = Socialite::driver('strava')->stateless()->user();
        $returnUrl = $request->session()->get('returnUrl');

//        $accessTokenResponseBody = $user->accessTokenResponseBody;
//        return [
//            'token' => $user,
//            'user' => $user->token,
//            'return' => $request->session()->get('returnUrl')
//        ];

        // when already signed

        // no login, register

        // finally, save token
        $token = new ProviderToken;
        $token->user_id = $request->user()->id;
        $token->provider = 'strava';
        $token->provider_id = $user->getId();
        $token->username = $user->getName();
        $token->token_type = 'Bearer';
        $token->access_token = $user->token;
        $token->refresh_token = $user->refreshToken;
        $token->expires_in = $user->expiresIn;
        $token->save();

        // and update user data

        // purge. and out
        Auth::logout();
        $request->session()->flush();
        return redirect()->away($returnUrl);
    }


}
