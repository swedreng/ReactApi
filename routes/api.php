<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signup', ['uses' => 'UserController@create']);

Route::delete('/users/delete/{id}', ['uses' => 'UserController@delete']);

Route::get('/users/{id}', ['uses' => 'UserController@get']);

Route::post('/users', ['uses' => 'UserController@getUser']);

Route::post('/users/login', ['uses' => 'UserController@index']);



