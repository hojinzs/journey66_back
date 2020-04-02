<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::domain('auth.'.env('APP_ROUTE_DOMAIN','localhost'))->name('auth.')->group(function(){
    Route::get('/',function () {
        return response('TEST',200);
    });

    Route::prefix('strava')->name('strava.')->group(function() {

        Route::get('/signup','StravaController@redirectTo')
            ->name('signup');

        Route::get('/integrate','StravaController@redirectTo')
            ->middleware('auth:token')
            ->name('integrate');

        Route::any('/authorized','StravaController@callback')
            ->name('callback');

    });
});


Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
