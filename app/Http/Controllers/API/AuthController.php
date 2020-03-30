<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('email',$request->email)->get()->first();
        return $this->setToken($user,'user-token');
    }

    public function register(Request $request){

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user = $this->create($request->all());
        return $this->setToken($user,'user-token');
    }

    /**
     * Set a User Token
     *
     * @param User $user
     * @param String $tokenName
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function setToken(User $user,$tokenName){
        $token = $user->createToken($tokenName);
        return response($token->plainTextToken,200);
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
        return User::forceCreate([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(30),
        ]);
    }

}
