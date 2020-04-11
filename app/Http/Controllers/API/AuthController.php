<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response('authentication information not found', 401);
        }

        return $this->returnUserData($user);
    }

    public function token()
    {
        return $this->returnUserData(Auth::user());
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user = $this->create($request->all());

        return $this->returnUserData($user);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::newUser($data);
    }

    /**
     * Response to client with user data
     *
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function returnUserData(User $user)
    {
        $token = $user->setToken('web-api-token');
        $response = [
            'user' => $user,
            'token' => $token,
            'provider' => [
                'strava' => $user->providerTokens()->where('provider','strava')->first(),
            ]
        ];
        return response($response,200);
    }


}
