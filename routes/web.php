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


Route::domain(env('APP_API_PREFIX').'.'.env('APP_ROOT_DOMAIN'))->name('api.')->group(function () {
    Route::get('/', function () {
        return response('welcome');
    });
});

Route::domain(env('APP_AUTH_PREFIX').'.'.env('APP_ROOT_DOMAIN'))->name('auth.')->group(function(){
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


Route::domain(env('APP_ADMIN_PREFIX').'.'.env('APP_ROOT_DOMAIN'))->name('api.')->group(function () {
//    Route::get('/', function () {
//        return response('welcome');
//    });

    Route::get('/', 'Admin\HomeController@index')->name('home');
});

//Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');
