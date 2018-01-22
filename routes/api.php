<?php

use Illuminate\Http\Request;



Route::post('/users/login', ['uses' => 'UserController@index']);
Route::post('/signup', ['uses' => 'UserController@create']);


Route::group(['middleware' => ['jwt.auth']], function () {
    
    Route::delete('/users/delete/{id}', ['uses' => 'UserController@delete']);

    Route::get('/users/{id}', ['uses' => 'UserController@get']);    

    Route::get('/users', ['uses' => 'UserController@getUser']);

    Route::post('/users/createpost', ['uses' => 'PostController@create']);


});

