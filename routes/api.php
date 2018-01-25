<?php

use Illuminate\Http\Request;

// Tokensiz requestler
    //posts
    Route::post('/login', ['uses' => 'UserController@index']);
    Route::post('/signup', ['uses' => 'UserController@create']);
    //gets
    Route::get('/posts', ['uses' => 'PostController@index']);


// Tokenli requestler 

Route::group(['middleware' => ['jwt.auth']], function () {

    //delete
    Route::delete('/delete/{id}', ['uses' => 'UserController@delete']);
    
    //gets
    Route::get('/users/picture', ['uses' => 'UserController@mypic']);
    Route::get('/users/{id}', ['uses' => 'UserController@get']);    
    Route::get('/users', ['uses' => 'UserController@getUser']);
    
    Route::get('/users/userinfo/{id}', ['uses' => 'UserController@getuserInfo']);
   
        // Tokensiz //
        // MainController //
    // POST /login => giriş yap // login
    // POST /signup => üye ol // signup
    // GET /posts => paylaşımları çek //index method

        // Kullanıcı işlemleri //
        // UserController //
    // GET /user => kullanıcı bilgileri gelsin // get method
    // PUT /user => kullanıcı bilgileri güncelleme // update method
    // POST /user => Ekstra bilgi ekleme telefon adres vb. // create method
    
        // User profil resmi //
        // UserController //
    // POST /user/ppupload => Profil resmi yükleme
    // DELETE /user/ppupload => Profil resmi kaldırma

        // User Post paylaşımı //
        // PostController
    // POST /user/post => Paylasım
    
        // Admin İşlemleri //
        // AdminController
    // GET /users => Kullanıcıları Listeleme
    // GET /users/:id => Tek bir kullanıcıyı listeleme
    // DELETE /users/:id => Kullanıcı silme
    

    //posts
    Route::post('/users/userinfoupdate', ['uses' => 'UserController@userinfoUpdate']);
    Route::post('/users/createpost', ['uses' => 'PostController@create']);
    Route::post('/users/pp', ['uses' => 'UserController@ppCreate']);
   


});

