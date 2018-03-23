<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 
use Validator;
use Socialite;

class FacebookLoginController extends Controller{


    public function redirectProvider($provider){
        
        return Socialite::driver($provider)->redirect();
        
    }
    public function ProviderCallback($provider){
        $user = Socialite::driver($provider)->user();
        return ['user' => $user];
    }
}
