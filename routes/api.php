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
Route::middleware('api')->group(function(){

    /**
     * Place Routes
     */
    Route::apiResource('places','API\PlaceController')
        ->only(['index','show']);

    Route::apiResource('places.recommends','API\PlaceRecommendController')
        ->only(['index','store','destroy']);
    Route::get('places/{place}/recommends/pinned','API\PlaceRecommendController@pinned')
        ->name('places.recommends.pinned');

    Route::apiResource('places.tags','API\PlaceTagController')
        ->only(['index','show']);

    Route::apiResource('places.tags.comments','API\PlaceTagCommentController')
        ->only(['index','store','destroy']);

    /**
     * Tag Routes
     */
    Route::apiResource('tags','API\TagController');
    Route::prefix('tags')->name('tags.')->group(function() {

    });

    /**
     * Like Routes
     */
    Route::post('places/{place}/like','API\LikeController@place')
        ->name('places.like');
    Route::post('places/recommends/{placeRecommend}/like','API\LikeController@placeRecommend')
        ->name('places.recommends.like');
    Route::post('places/tags/comments/{placeTagComment}/like','API\LikeController@placeTagCommment')
        ->name('places.tags.comments.like');


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
         * Logout
         */
        Route::post('logout','API\AuthController@logout')
            ->middleware('auth:sanctum')
            ->name('logout');

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

        /**
         * User Owned Resources (Places)
         */
        Route::prefix('places')->name('places.')->group(function() {
            Route::get('likes', 'API\UserController@placeByLikes')
                ->middleware('auth:sanctum')
                ->name('likes');
        });
        Route::prefix('places')->name('place.')->group(function() {
            Route::get('{place}/recommends','API\PlaceRecommendController@owned')
                ->middleware('auth:sanctum')
                ->name('recommends');

            Route::get('{place}/tags/comments','API\PlaceTagCommentController@owned')
                ->middleware('auth:sanctum')
                ->name('tags.comments');
        });

    });

    /**
     * TEST
     */
    Route::middleware('api')->get('/test', function (Request $request) {
        return response($request,200);
    });

});
