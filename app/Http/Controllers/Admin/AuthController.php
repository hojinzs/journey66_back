<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    //
    public function show()
    {
        return view('admin.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

//        if (Auth::guard('admin')->attempt($credentials)) {
        if (Auth::attempt($credentials)) {
            // Authentication passed...

            // send Admin API Token..
            $user = Auth::user();
            $token = $user->createToken('web-admin-token')->plainTextToken;
            \Illuminate\Support\Facades\Cookie::queue('Authorization',$token,'1','/',env('SESSION_DOMAIN'),false,false);

            return redirect()->intended('admin.app');
        } else {
            return response("FAIL",401);
        }
    }
}
