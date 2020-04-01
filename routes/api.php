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

Route::domain('api.'.env('APP_ROUTE_DOMAIN','localhost'))->group(function(){

    /**
     * Place Routes
     */
    Route::apiResource('places','API\PlaceController');
    Route::prefix('places')->name('places.')->group(function() {

        Route::get('/{place}/tags','API\PlaceController@getTags')
            ->name('tags');

        Route::get('/{place}/tags/{tag}/comments','API\PlaceController@getTagsComments')
            ->name('tags.comments');

        Route::get('/{place}/recommends','API\PlaceController@getRecommends')
            ->name('recommends');

    });

    /**
     * Tag Routes
     */
    Route::apiResource('tags','API\TagController');
    Route::prefix('tags')->name('tags.')->group(function() {

    });

    /**
     * USER Routes
     */
    Route::prefix('user')->name('user.')->group(function() {

        Route::middleware('auth:sanctum')
            ->get('/','API\UserController@whoami')
            ->name('whoami');

        Route::post('/login','API\AuthController@login')
            ->name('login');

        Route::post('/signup', 'API\AuthController@register')
            ->name('register');

    });


    /**
     * TEST
     */
    Route::middleware('api')->get('/test', function (Request $request) {
        return response("TEST",200);
    });

});
