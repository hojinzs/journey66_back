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
Route::middleware(['api', 'auth:sanctum','auth:admin'])->group(function(){

    /**
     * Users
     */
    Route::apiResource('users','API\UserController')
        ->only('index','show');

    /**
     * Places
     */
    Route::prefix('places')->name('places.')->group(function(){
        Route::apiResource('types','API\PlaceTypeController');
    });
    Route::apiResource('places','API\PlaceController')
        ->only('index','show','store','update');

    /**
     * Tags
     */
    Route::prefix('tags')->name('tags.')->group(function(){
        Route::apiResource('types','API\TagTypeController');
    });
    Route::apiResource('tags','API\TagController')
        ->only('index','show','store','update');

    /**
     * Options
     */
    Route::prefix('options')->name('options.')->group(function(){
        Route::get('','API\OptionController@list')
            ->name('list');
        Route::post('','API\OptionController@create')
            ->name('create');

        Route::get('/{table}','API\OptionController@index')
            ->name('index');
        Route::get('/{table}/{column}','API\OptionController@show')
            ->name('show');
        Route::post('/{table}/{column}','API\OptionController@store')
            ->name('store');
        Route::put('/{table}/{column}/_order','API\OptionController@order')
            ->name('order');
        Route::put('/{table}/{column}/{id}','API\OptionController@update')
            ->name('update');
        Route::delete('/{table}/{column}/{option}','API\OptionController@destroy')
            ->name('destroy');

    });

    /**
     * TEST
     */
    Route::get('/test', function (Request $request) {
        return response($request->user(),200);
    })->name('test');

});
