<?php

use Illuminate\Http\Request;

// Tokensiz requestler
    //posts
    Route::post('/users/login', ['uses' => 'UserController@index']);
    Route::post('/signup', ['uses' => 'UserController@create']);
    //gets
    Route::get('/posts', ['uses' => 'PostController@index']);


// Tokenli requestler 

Route::group(['middleware' => ['jwt.auth']], function () {

    //delete
    Route::delete('/users/delete/{id}', ['uses' => 'UserController@delete']);
    
    //gets
    Route::get('/users/picture', ['uses' => 'UserController@mypic']);
    Route::get('/users/{id}', ['uses' => 'UserController@get']);    
    Route::get('/users', ['uses' => 'UserController@getUser']);
    
    Route::get('/users/userinfo/{id}', ['uses' => 'UserController@getuserInfo']);
   

    // Kullanıcı işlemleri

    // GET /user => kullanıcı bilgileri gelsin
    // POST /user => Kullanıcı kaydı
    // PUT /user => kullanıcı bilgileri güncelleme
    // POST /user/create => Post işlemleri


    // Kullanıcılar İşlemleri

    // GET /users => Kullanıcıları Listeleme
    // GET /users/:id => Tek bir kullanıcıyı listeleme
    // DELETE /users/:id => Kullanıcı silme
    

    //posts
    Route::post('/users/userinfoupdate', ['uses' => 'UserController@userinfoUpdate']);
    Route::post('/users/createpost', ['uses' => 'PostController@create']);
    Route::post('/users/pp', ['uses' => 'UserController@ppCreate']);
   


});

