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

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::post('/products', 'ProductController@store');

    Route::get('/products/{id}', 'ProductController@show');

    Route::put('/products/{id}', 'ProductController@update');

    Route::delete('/products/{id}', 'ProductController@destroy');
});
