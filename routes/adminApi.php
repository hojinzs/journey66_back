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
        Route::get('icon_prefix','API\OptionController@getTagIconPrefix')
            ->name('get_icon_prefix');
        Route::post('icon_prefix','API\OptionController@dispatchTagIconPrefix')
            ->name('set_icon_prefix');
    });
    Route::apiResource('tags','API\TagController')
        ->only('index','show','store','update');

    /**
     * Options
     */
    Route::prefix('options')->name('options.')->group(function(){
        Route::get('/{table}','API\OptionController@index')
            ->name('index');
        Route::get('/{table}/{column}','API\OptionController@show')
            ->name('show');
        Route::post('/{table}/{column}','API\OptionController@store')
            ->name('store');
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
