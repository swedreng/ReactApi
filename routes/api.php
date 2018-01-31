<?php

use Illuminate\Http\Request;

// Tokensiz requestler
    //posts
    Route::post('/login', ['uses' => 'MainController@login']); //+
    Route::post('/signup', ['uses' => 'MainController@signup']); // +
    //gets
    Route::get('/posts', ['uses' => 'MainController@index']); // +


// Tokenli requestler 

Route::group(['middleware' => ['jwt.auth']], function () {
   
    //gets
    Route::delete('/users/{id}', ['uses' => 'AdminController@delete']); // +   
    Route::get('/users', ['uses' => 'AdminController@getUser']); // +
    Route::get('/user', ['uses' => 'UserController@get']); // +
   
    //posts
    Route::post('/user/pp', ['uses' => 'UserController@pp']); // +
    Route::put('/user', ['uses' => 'UserController@update']); // +
    Route::delete('/user', ['uses' => 'UserController@ppdelete']); // yapılacak route u yapıldı.
    Route::post('/user/createpp', ['uses' => 'PostController@createpp']); // createpp = create picture post //+
    Route::post('/user/createwp', ['uses' => 'PostController@createwp']); // createwp = create write post // yapılacak route u ve fonskiyonu olusturuldu.
    Route::post('/user/deletepost', ['uses' => 'PostController@delete']); // yapılacak route u yapıldı.
    Route::post('/comment', ['uses' => 'CommentController@index']);
    Route::get('/comment', ['uses' => 'CommentController@commentUpdate']);
      // Tokensiz //
        // MainController //
    // POST /login => giriş yap // login // + 
    // POST /signup => üye ol // signup // + 
    // GET /posts => paylaşımları çek //index method // +

        // Kullanıcı işlemleri //
        // UserController //
    // GET /user => kullanıcı bilgileri gelsin // get method +
    // PUT /user => kullanıcı bilgileri güncelleme // update method +
    // POST /user => Ekstra bilgi ekleme telefon adres vb. // create method // yapılcak
    
        // User profil resmi //
        // UserController //
    // POST /user/pp => Profil resmi yükleme // +
    // DELETE /user => Profil resmi kaldırma // route tanımlandı fonksiyonda tanımlandı ama işlevsellik katılmadı.

        // User Post paylaşımı //
        // PostController
    // POST /user/createpost => Resim paylaşımı // + 
    // POST /user/createwp => Yazı paylaşımı // route olusturuldu.
    // DELETE /user/deletepost => Paylaşım silme // route ve fonksiyon olusturuldu.

        // Admin İşlemleri //
        // AdminController
    // GET /users => Kullanıcıları Listeleme // +
    // GET /users/:id => Tek bir kullanıcıyı listeleme
    // DELETE /users/:id => Kullanıcı silme // +


});

