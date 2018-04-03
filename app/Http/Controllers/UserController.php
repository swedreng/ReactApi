<?php
namespace App\Http\Controllers;
use JWTAuth;
use Validator;
use App\Models\Users;
use App\Models\Posts;
use App\Http\Controllers\Controller; 
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Http\Requests\SignupPostRequest;
use Illuminate\Http\Validation\ValidEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Http\Response;
use App\Http\Requests\FileUploadPostRequest; 
use App\Http\Requests\UserEmailUpdate; 
use App\Http\Requests\UserUsernameUpdate; 
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function index(Request $request){
       
        
    }

    public function get (){ // +
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->findorFail($user->id);
        return $query;
    }

    public function getUserposts(Request $request){
        $postReq = $request->input('postReq');
        $status = $request->input('status');
        $user = JWTAuth::parseToken()->authenticate();
        $model = new Posts;
        $userPost = $model->where('id', '=' , $user->id)->get();
        $postCount = count($userPost);
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->with(['User','Likes'])->where('id','=',$user->id)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        return ['data' => $query,
        'postCount' => $postCount,
        'event' => $status];
    }
    
    public function userInfoupdate(Request $request){ 
        $value = $request->input('value');
        $status = $request->input('status');
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->where('id','=',$user->id)->first();
        switch($status){
            case 1:
                $query->firstname = $value;
                $query->save();
                return ['message' => "Basarıyla isminizi güncellediniz.",
                        'success' => true];
            break;
            default:
                $query->lastname = $value;
                $query->save();
                return ['message' => "Basarıyla soyisminizi güncellediniz.",
                        'success' => true];
            break;    
        }      
    }
    public function userEmailUpdate(UserEmailUpdate $request){
        $email = $request->input('email');
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->where('id','=',$user->id)->first();
        $query->email = $email;
        $query->save();
        return ['message' => "Basarıyla email adresinizi güncellediniz.",
                'success' => true];
    }

    public function UsernameUpdate(UserUsernameUpdate $request){
        $username = $request->input('username');
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->where('id','=',$user->id)->first();
        $query->username = $username;
        $query->save();
        return ['message' => "Basarıyla kullanıcı adınızı güncellediniz.",
                'success' => true];
    }

    public function passwordUpdate(Request $request){
        $oldpassword = $request->input('oldpassword');
        $newpassword = $request->input('newpassword');
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->where('id','=',$user->id)->first();
        $result = Hash::check($oldpassword,$query->password);
        
        if($result){
            $query->password = $newpassword;
            $query->save();
            return ['message' => "Basarıyla şifrenizi güncellediniz.",
                    'success' => true];  
        }else{
            return ['message' => "Lütfen eski şifrenizi doğru giriniz.",
                    'success' => false];
        }
          
    }

    public function pp(FileUploadPostRequest $request){ // +
        
        $files = $request->file('files');
        $extension = $files->getClientOriginalExtension(); //jpg
        Storage::disk('public')->put($files->getClientOriginalName(), File::get($files));
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $model = $model->findOrFail($user->id);
        $model->pp = Storage::url($files->getClientOriginalName());
        $result = $model->save();
        $user_info = $model->where('username', "=", $user->username)->first();
       
        if($result){
            return ['message' => 'Profil resminiz basarıyla güncellendi.',
                    'success' => true,
                    'user_pp' => $user_info->pp];
        }else{
            return ['message' => 'Profil resminiz güncellenirken bir sorun olustu.',
                    'success' => false];
        }
    }

    public function setUserInfo(Request $request){
        $phone = $request->input('phone');
        $adress = $request->input('adress');
        $personalwriting = $request->input('personalwriting');
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->where('id','=',$user->id)->first();
        $query->phone = $phone;
        $query->adress = $adress;
        $query->personalwriting = $personalwriting;
        $query->save();
        return ['message' => "Basarıyla bilgilerinizi güncellediniz.",
                'success' => true];
    }

   
 
}