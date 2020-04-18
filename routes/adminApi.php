<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::domain(env('APP_ADMIN_PREFIX').'.'.env('APP_ROOT_DOMAIN','localhost'))->name('admin.api.')->group(function(){

    /**
     * Users
     */
    Route::apiResource('users','API\UserController')
        ->only('index','show');

    /**
     * Places
     */
    Route::apiResource('places','API\PlaceController')
        ->only('index','show','store','update');

    /**
     * Tags
     */
    Route::apiResource('tags','API\TagController')
        ->only('index','show');

    /**
     * TEST
     */
    Route::get('/test', function (Request $request) {
        return response($request->user(),200);
    })->name('test');

});
