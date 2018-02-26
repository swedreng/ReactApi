<?php
namespace App\Http\Controllers;
use JWTAuth;
use Validator;
use App\Models\Users;
use App\Models\Posts;
use App\Models\BlockUser;
use App\Models\Comments;
use App\Models\BlockPost;
use App\Http\Controllers\Controller; 
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginPostRequest; 
use App\Http\Requests\SignupPostRequest;

class MainController extends Controller {

    public function index(Request $request){
        $postReq = $request->input('postReq');
        $status = $request->input('status');
        $user = JWTAuth::parseToken()->authenticate();
        $blockModel = new BlockPost;
        $model = new Posts;
        $blockPost = $blockModel->where('user_id', '=' , $user->id)->get();
        $totalPost = $model->get();
        $postCount = count($totalPost) - count($blockPost);
        $query = Users::where('id','=',$user->id)->first();
        if($query->rank == 0){
            $query = $model->with(['User','Likes'])->where('confirmation','=',1)->orWhere('id','=',$user->id)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        }else{
            $query = Posts::leftJoin('block_post', function ($join) use ($user){
                $join->on('posts.post_id', '=', 'block_post.post_id')->where('block_post.user_id','=',$user->id);
            })->whereRaw('block_post.user_id IS NULL')->select('posts.*')->with(['User','Likes'])->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();   
        }
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