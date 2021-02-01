<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {
    Route::post('login','ConnectController@login');
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('refresh','ConnectController@refreshToken');
        Route::get('expire','ConnectController@expireToken');
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('/tasks','TaskController@store');
        Route::get('/tasks','TaskController@index');
        Route::put('/tasks/{id}','TaskController@update');
        Route::delete('/tasks/{id}','TaskController@destroy');
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('/products','ProductController@store');
        Route::get('/products','ProductController@index');
        Route::put('/products/{id}','ProductController@update');
        Route::delete('/products/{id}','ProductController@destroy');
    });
});

