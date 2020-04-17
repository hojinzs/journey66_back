<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    //
    public function show(Request $request)
    {
        return view('admin.auth.login',[
            'status' => $request->query('status')
        ]);
    }

    public function who(Request $request)
    {
        return dd($request->user());
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(Auth::guard('admin')->validate($credentials)) {
            // Authentication passed...

            // send Admin API token cookie
            $user = Auth::guard('admin')->user();
            Auth::login($user);

            $token = $user->createToken('web-admin-token')->plainTextToken;
//            Cookie::queue('Authorization',$token,'1','/',env('SESSION_DOMAIN'),false,false);

            return redirect()->intended('/')->withCookie('Authorization',$token,'30','/',env('SESSION_DOMAIN'),false,false);
        }

        return redirect()->route('admin.login',['status' => 'failed']);
    }

    public function logout(Request $request)
    {
        Auth::logout($request->user());
        $request->session()->flush();

        return redirect()->route('admin.login',['status' => 'logout'])->withCookie('Authorization','',-1,env('SESSION_DOMAIN'),false,false);
    }
}
