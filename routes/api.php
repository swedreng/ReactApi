<?php

use Illuminate\Http\Request;

// Tokensiz requestler

Route::post('/users/login', ['uses' => 'UserController@index']);
Route::post('/signup', ['uses' => 'UserController@create']);
Route::get('/posts', ['uses' => 'PostController@index']);

// Tokenli requestler 

Route::group(['middleware' => ['jwt.auth']], function () {
    
    Route::delete('/users/delete/{id}', ['uses' => 'UserController@delete']);

    Route::get('/users/{id}', ['uses' => 'UserController@get']);    

    Route::get('/users', ['uses' => 'UserController@getUser']);
    Route::get('/users/userinfo/{id}', ['uses' => 'UserController@getuserInfo']);

    Route::post('/users/createpost', ['uses' => 'PostController@create']);
    Route::post('/users/userinfoupdate', ['uses' => 'UserController@userinfoUpdate']);


});

