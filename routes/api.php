<?php

use Illuminate\Http\Request;

// Tokensiz requestler
    //posts
    Route::post('/login', ['uses' => 'MainController@login']); //+
    Route::get('/full', 'NoLoginMainController@allPosts');
    Route::post('/rememberme', ['uses' => 'NoLoginMainController@rememberMe']); //+
    Route::post('/forgetme', ['uses' => 'NoLoginMainController@forgetMe']); //+
    Route::post('/getrememberme', ['uses' => 'NoLoginMainController@getRememberMe']); //+
    Route::post('/signup', ['uses' => 'MainController@signup']); // +
    Route::post('/postsdefault', ['uses' => 'NoLoginMainController@index']);
    Route::post('/outgetcomment', ['uses' => 'NoLoginMainController@getComments']);
    Route::post('/contact', ['uses' => 'NoLoginMainController@contact']);
    Route::post('/nologinsearch',['uses' => 'NoLoginMainController@Search']);
    Route::post('/searchperson',['uses' => 'NoLoginMainController@searchPerson']);
    Route::post('/viewprofile',['uses' => 'NoLoginMainController@viewProfile']);
    Route::post('/passwordreset',['uses' => 'NoLoginMainController@passwordReset']);
    Route::post('/passwordupdate',['uses' => 'NoLoginMainController@passwordUpdate']);
    Route::post('/post/getcategory', ['uses' => 'ModeratorController@getCategory']);
    Route::get('/bestposttoday', ['uses' => 'NoLoginMainController@bestPostToday']);
    Route::post('/getbestpost', ['uses' => 'NoLoginMainController@bestPost']);
    Route::post('/topbestposttoday', ['uses' => 'NoLoginMainController@topBestPost']);
    Route::post('/user/getviewsocialmedia', ['uses' => 'UserController@getViewSocialMedia']); 
Route::group(['middleware' => ['web']], function () {
    Route::get('auth/{provider}', 'FacebookLoginController@redirectProvider');
    Route::get('auth/{provider}/callback', 'FacebookLoginController@ProviderCallback');
});
// Tokenli requestler 

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::post('/loginviewprofile',['uses' => 'MainController@LoginviewProfile']);
    Route::post('/loginsearch',['uses' => 'MainController@search']);
    Route::post('/loginsearchperson',['uses' => 'MainController@loginSearchPerson']);
    Route::post('/posts', ['uses' => 'MainController@index']);
    Route::post('/post/confirmation', ['uses' => 'PostController@postConfirmation']);
    Route::post('/post/blockPost', ['uses' => 'PostController@blockPost']);
    Route::post('/post/blockUser', ['uses' => 'PostController@blockUser']);
    Route::post('/post/userconfirmation', ['uses' => 'PostController@userConfirmation']);
    Route::post('/post/setcategory', ['uses' => 'ModeratorController@setCategory']);
    Route::post('/topbestposttodaylogin', ['uses' => 'MainController@topBestPostLogin']);
    Route::post('/logingetbestpost', ['uses' => 'PostController@getBestPostLogin']);
    
    Route::post('/postlike', ['uses' => 'PostController@Like']);
    //gets
    Route::delete('/users/{id}', ['uses' => 'AdminController@delete']); // +   
    Route::get('/users', ['uses' => 'AdminController@getUser']); // +
    Route::get('/user', ['uses' => 'UserController@get']); // +
    Route::post('/userposts',['uses' => 'UserController@getUserposts']);
  
    //posts
    Route::post('/user/pp', ['uses' => 'UserController@pp']); // +
    Route::put('/user', ['uses' => 'UserController@userInfoupdate']);
    Route::put('/user/emailupdate', ['uses' => 'UserController@userEmailUpdate']);
    Route::get('/user/blockusers', ['uses' => 'UserController@blocUsers']);
    Route::get('/user/isblockpost', ['uses' => 'UserController@isBlockPost']);
    Route::post('/user/notblockuser', ['uses' => 'UserController@notBlockUser']);
    Route::put('/user/usernameupdate', ['uses' => 'UserController@UsernameUpdate']);
    Route::put('/user/passwordupdate', ['uses' => 'UserController@passwordUpdate']); 
    Route::post('/user/setuserinfo', ['uses' => 'UserController@setUserInfo']);
    Route::post('/user/setsocialmedia', ['uses' => 'UserController@setSocialMedia']); 
    Route::get('/user/getsocialmedia', ['uses' => 'UserController@getSocialMedia']); 
    Route::post('/user/getshareInfo', ['uses' => 'UserController@getShareInfo']);
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

