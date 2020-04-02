<?php
/**
 * Strava API Controller
 *
 * Reference > Strava Develop Guide ( https://developers.strava.com )
 * Auth :: https://developers.strava.com/docs/getting-started/
 * API Doc :: https://developers.strava.com/docs/reference/
 */

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
    /**
     * Strava oauth page redirect
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectTo(Request $request) {

        $request->session()->put('returnUrl',$request->query('return_url'));

        $social = Socialite::driver('strava');

        if($request->user()) {
            Auth::guard('web')->login($request->user());
        }

        if($request->query('scope') == 'activity_read') {
            $scope = ['activity:read'];
            $request->session()->put('scope','activity_read');
            $social->scopes($scope);
        }

        return $social->redirect();
    }

    /**
     * Get Strava oauth callback and processing Token
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request) {

        $user = Socialite::driver('strava')->stateless()->user();

        // set token scope
        if($request->session()->get('scope')){
            $scope = $request->session()->get('scope');
        } else {
            $scope = 'read';
        }

        // find user already exist
        $getCurrentToken = ProviderToken::where('provider','strava')->where('provider_id',$user->getId())->first();
        if($getCurrentToken){
            if(!$scope == 'read'){
                $this->updateProviderToken($getCurrentToken, $user, $scope);
            }

            Auth::guard('web')->login(User::find($getCurrentToken->user_id));

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

            // and set up profile image
            $this->setUserProfile($user, Auth::user());
        }

        // finally, save token
        $this->setProviderToken($user, Auth::user(), $scope);

        // out
        return $this->redirect($request);
    }

    /**
     * Redirect to App with Authorization Token (use Cookie)
     *
     * @param Request $request
     * @param null $returnUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    private static function redirect(Request $request,$returnUrl = null)
    {

        if($returnUrl == null){
            $returnUrl = $request->session()->get('returnUrl');
        }
        $token = User::find(Auth::id())->setToken('web-api-token');

        \Illuminate\Support\Facades\Cookie::queue('Authorization',$token,'1','/','.bikegear.test',false,false);

        Auth::logout();
        $request->session()->flush();

        return redirect()->away($returnUrl);
    }

    /**
     * save Strava Provider Token
     *
     * @param \SocialiteProviders\Manager\OAuth2\User $social
     * @param User $user
     * @param $scope
     * @return ProviderToken
     */
    private static function setProviderToken(\SocialiteProviders\Manager\OAuth2\User $social, User $user, $scope)
    {
        $token = new ProviderToken;
        $token->user_id = $user->id;
        $token->provider = 'strava';
        $token->provider_id = $social->getId();
        $token->scope = $scope;
        $token->username = $social->getName();
        $token->token_type = 'Bearer';
        $token->access_token = $social->token;
        $token->refresh_token = $social->refreshToken;
        $token->expires_in = $social->expiresIn;
        $token->save();

        return $token;
    }

    /**
     * update the Token
     *
     * @param ProviderToken $old_token
     * @param \SocialiteProviders\Manager\OAuth2\User $new_token
     * @param $scope
     * @return ProviderToken
     */
    private static function updateProviderToken(ProviderToken $old_token, \SocialiteProviders\Manager\OAuth2\User $new_token, $scope)
    {
        $old_token->scope = $scope;
        $old_token->access_token = $new_token->token;
        $old_token->refresh_token = $new_token->refreshToken;
        $old_token->expires_in = $new_token->expiresIn;
        $old_token->update();

        return $old_token;
    }

    /**
     * set user profile image from Strava Profile
     *
     * @param \SocialiteProviders\Manager\OAuth2\User $social
     * @param User $user
     * @return User
     */
    private static function setUserProfile(\SocialiteProviders\Manager\OAuth2\User $social, User $user)
    {
        $user->profile = $social->user['profile'];
        $user->profile_medium = $social->user['profile_medium'];
        $user->update();

        return $user;
    }



}
