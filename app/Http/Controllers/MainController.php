<?php
namespace App\Http\Controllers;
use JWTAuth;
use Validator;
use App\Models\Users;
use App\Models\Posts;
use App\Models\Comments;
use App\Http\Controllers\Controller; 
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginPostRequest; 
use App\Http\Requests\SignupPostRequest;

class MainController extends Controller {

    public function index(Request $request){
        $postReq = $request->input('postReq');
        $status = $request->input('status');
        $model = new Posts;
        $result = $model->get();
        $postCount = count($result);
        $query = $model->with(['User','Likes'])->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        return ['data' => $query,
                'postCount' => $postCount,
                'event' => $status];
    }
    
    public function noLogin(Request $request){
        $postReq = $request->input('postReq');
        $model = new Users;
        $result = $model->get();
        return $result;
        $postCount = count($result);
        $query = $model->with(['User','Likes'])->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        return ['data' => $query,
                'postCount' => $postCount,
                'event' => $status];
    }

    public function login(LoginPostRequest $request){
        
        $model = new Users;
        $username = $request->input('username');
        $user_info = $model->where('username', "=", $username)->first();
       
        if(isset($user_info->username)){
            $credentials = $request->all();
                try{
                    $token = JWTAuth::attempt($credentials);
                    if(!$token){
                        return  [
                            'message' => 'Bu kullanıcı adıyla şifre eşleşmiyor lütfen doğru bilgiler giriniz.',
                            'success' => false];
                    }
                   
                        return [
                            'message' => 'Basarıyla giriş yaptınız.',
                            'success' => true,
                            'token' => $token,
                            'username' => $username,
                            'role' => $user_info->rank,
                            'user_id' => $user_info->id,
                            'user_pp' => $user_info->pp];
        
                }catch (JWTException $e) {
                                
                        return [
                            'message' => 'Kullanıcı adına token olusturulamadı.',
                            'success' => false];
                }
            
        }else{
            return [
                'message' => 'Bu kullanıcı adına kayıt bulunamadı.',
                'success' => false];
        }
        
    }

    public function signup(SignupPostRequest $request){

        $model = new Users;
        $query = $model->create($request->all());
        $result = $query->findOrFail($query->id);
        if($result){
            return ['message' => 'Basarıyla kayıt oldunuz.',
                    'success' => true];
        }else{
            return ['message' => 'Kayıt olamadınız bir sorun olustu lütfen daha sonra tekrar deneyiniz.',
                    'success' => false];
        }
    }

}