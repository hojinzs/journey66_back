<?php

namespace App\Http\Controllers;

use App\User;
use App\ProviderToken;
use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class StravaController extends Controller
{
    //
    public function redirectTo(Request $request){
        $request->session()->put('returnUrl',$request->query('return_url'));

        if($request->user()){
            Auth::guard('web')->login($request->user());
        }

        return Socialite::with('strava')->redirect();
    }

    public function callback(Request $request) {

        $user = Socialite::driver('strava')->stateless()->user();

        // find user already exist
        $getCurrentUser = ProviderToken::where('provider','strava')->where('provider_id',$user->getId())->first();
        if($getCurrentUser){
            Auth::guard('web')->login(User::find($getCurrentUser->user_id));
            return $this->redirect($request);
        }

        // no login, register
        if(!Auth::check()){
            $newUser = User::newUser([
                'name' => $user->getName(),
                'email' =>  Str::random(12).'@journey66.cc',
                'password' => Str::random(20)
            ]);
            Auth::guard('web')->login($newUser);
        }

        // finally, save token
        $this->setProviderToken($user, Auth::user());

        // and update user data
//        $this->setUserProfile($user, Auth::user());

        // out
        return $this->redirect($request);
    }

    private static function redirect(Request $request,$returnUrl = null)
    {
        if($returnUrl == null){
            $returnUrl = $request->session()->get('returnUrl');
        }
        $token = User::find(Auth::id())->setToken('web-api-token');

        \Illuminate\Support\Facades\Cookie::queue('Authorization',$token,'session','/','.bikegear.test',false,false);

        Auth::logout();
        $request->session()->flush();

        return redirect()->away($returnUrl);
    }

    private static function setProviderToken(\SocialiteProviders\Manager\OAuth2\User $social, User $user)
    {
        $token = new ProviderToken;
        $token->user_id = $user->id;
        $token->provider = 'strava';
        $token->provider_id = $social->getId();
        $token->username = $social->getName();
        $token->token_type = 'Bearer';
        $token->access_token = $social->token;
        $token->refresh_token = $social->refreshToken;
        $token->expires_in = $social->expiresIn;
        $token->save();

        return $token;
    }

    private static function setUserProfile(\SocialiteProviders\Manager\OAuth2\User $social, User $user)
    {

    }



}
