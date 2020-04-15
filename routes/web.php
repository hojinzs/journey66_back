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


Route::domain(env('APP_ADMIN_PREFIX').'.'.env('APP_ROOT_DOMAIN'))->name('admin.')->group(function () {

    Route::get('/login', 'Admin\AuthController@show')->name('login');
    Route::post('/login', 'Admin\AuthController@authenticate')->name('authenticate');
    Route::get('/logout', 'Admin\AuthController@logout')->name('logout');
    Route::get('/who', 'Admin\AuthController@who')->name('who');

    /**
     * 인증을 위해 필요한 라우트 외 나머지 라우트는 VueRouter 에서 처리한다.
     */
    Route::fallback('Admin\HomeController@index')->name('app');
});

//Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');
