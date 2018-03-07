<?php

use Illuminate\Http\Request;

// Tokensiz requestler
    //posts
    Route::post('/login', ['uses' => 'MainController@login']); //+
    Route::post('/signup', ['uses' => 'MainController@signup']); // +
    Route::post('/postsdefault', ['uses' => 'NoLoginMainController@index']);
    Route::post('/outgetcomment', ['uses' => 'NoLoginMainController@getComments']);
    Route::post('/contact', ['uses' => 'NoLoginMainController@contact']);
    Route::post('/search',['uses' => 'NoLoginMainController@Search']);
// Tokenli requestler 

Route::group(['middleware' => ['jwt.auth']], function () {
    
    Route::post('/posts', ['uses' => 'MainController@index']);
    Route::post('/post/confirmation', ['uses' => 'PostController@postConfirmation']);
    Route::post('/post/blockPost', ['uses' => 'PostController@blockPost']);
    Route::post('/post/blockUser', ['uses' => 'PostController@blockUser']);
    Route::post('/postlike', ['uses' => 'PostController@Like']);
    //gets
    Route::delete('/users/{id}', ['uses' => 'AdminController@delete']); // +   
    Route::get('/users', ['uses' => 'AdminController@getUser']); // +
    Route::get('/user', ['uses' => 'UserController@get']); // +
    Route::post('/userposts',['uses' => 'UserController@getUserposts']);
  
    //posts
    Route::post('/user/pp', ['uses' => 'UserController@pp']); // +
    Route::put('/user', ['uses' => 'UserController@update']); // +
    Route::delete('/user', ['uses' => 'UserController@ppdelete']); // yapılacak route u yapıldı.
    Route::post('/user/createpp', ['uses' => 'PostController@createpp']); // createpp = create picture post //+
    Route::post('/user/createwp', ['uses' => 'PostController@createwp']); // createwp = create write post // yapılacak route u ve fonskiyonu olusturuldu.
    Route::delete('/user/deletepost', ['uses' => 'PostController@delete']); // yapılacak route u yapıldı.
    Route::delete('/user/deletecomment', ['uses' => 'CommentController@delete']);
    Route::put('/user/updatecomment', ['uses' => 'CommentController@update']);
    
    Route::post('/getcomment',['uses' => 'CommentController@getComment']);
    Route::post('/comment', ['uses' => 'CommentController@index']);
    Route::post('/commentlast', ['uses' => 'CommentController@commentLastUpdate']);
    Route::get('/comment', ['uses' => 'CommentController@commentUpdate']);
    Route::put('/comment', ['uses' => 'CommentController@Like']);

    
    
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

