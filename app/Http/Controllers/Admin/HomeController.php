<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
//        return dd($request->user());
        if($request->user() && Auth::guard('admin')->setUser($request->user())->role('admin'))
        {
            return view('admin/app');
        } else {
            return redirect()->route('admin.login',['status' => 'required']);
        }
    }
}
