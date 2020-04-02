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

        Route::get('/','API\UserController@whoami')
            ->middleware('auth:sanctum')
            ->name('whoami');

        /**
         * Login
         */
        Route::prefix('login')->name('login.')->group(function() {

            Route::post('token','API\AuthController@token')
                ->middleware('auth:sanctum')
                ->name('login');

            Route::post('email','API\AuthController@login')
                ->name('login');

        });

        /**
         * Register
         */
        Route::post('/signup', 'API\AuthController@register')
            ->name('register');

        /**
         * Provider(ex: STRAVA) Manage
         */
        Route::prefix('provider')->name('provider.')->group(function() {

            Route::get('','API\ProviderController@show')
                ->middleware('auth:sanctum')
                ->name('show');

            // Provider Token Refreash
            Route::post('refresh','API\ProviderController@update')
                ->middleware('auth:sanctum')
                ->name('refresh');

        });

    });


    /**
     * TEST
     */
    Route::middleware('api')->get('/test', function (Request $request) {
        return response("TEST",200);
    });

});
