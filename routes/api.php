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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('places','API\PlaceController');

Route::middleware('api')
    ->get('/places/{id}/tags','API\PlaceController@getTags');

Route::middleware('api')
    ->get('/places/{id}/tags/{tagId}/comments','API\PlaceController@getTagsComments');

Route::middleware('api')
    ->get('/places/{id}/recommends','API\PlaceController@getRecommends');


Route::apiResource('tags','API\TagController');

Route::middleware('api')->get('/test', function (Request $request) {
    return response("TEST",200);
});