<?php
namespace App\Http\Controllers;
use JWTAuth;
use Validator;
use App\Models\Users;
use App\Models\Posts;
use App\Models\Comments;
use App\Models\UserInfo;
use App\Models\PostCategory;
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


    public function get (){
        $model = new Users;
        $modelPost = new Posts;
        $modelComment = new Comments;
        $user = JWTAuth::parseToken()->authenticate();
        $posts = $modelPost->where([['id','=',$user->id],['confirmation','=',1]])->get();
        $comments = $modelComment->where('id','=',$user->id)->get();
        $postCount = count($posts);
        $commentCount = count ($comments);
        $query = $model->findorFail($user->id);

        return ['user_info' => $query,
                'commentCount' => $commentCount,
                'postCount' => $postCount];
    }

    public function getUserposts(Request $request){
        $postReq = $request->input('postReq');
        $status = $request->input('status');
        $user = JWTAuth::parseToken()->authenticate();
        $model = new Posts;
        $userPost = $model->where('id', '=' , $user->id)->get();
        $postCount = count($userPost);
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->with(['User','Likes','PostCategory'])->where('id','=',$user->id)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
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
                        'success' => true,
                        'status' => 1];
            break;
            default:
                $query->lastname = $value;
                $query->save();
                return ['message' => "Basarıyla soyisminizi güncellediniz.",
                        'success' => true,
                        'status' => 2];
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
        $value = $request->input('value');
        $status = $request->input('status');
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->where('id','=',$user->id)->first();
        switch($status){
            case 1:
                $query->phone = $value;
                $query->save();
                return ['message' => "Basarıyla telefonunuzu güncellediniz.",
                        'success' => true,
                        'status' => 1];
            break;
            case 2:
                $query->adress = $value;
                $query->save();
                return ['message' => "Basarıyla adresinizi güncellediniz.",
                        'success' => true,
                        'status' => 2];
            break;
            default:
                $query->personalwriting = $value;
                $query->save();
                return ['message' => "Basarıyla kişisel yazınızı güncellediniz.",
                        'success' => true,
                        'status' => 3];
            break;    
        }      

    }


    public function setSocialMedia(Request $request){
        $value = $request->input('value');
        $status = $request->input('status');
        $model = new UserInfo;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->where('user_id','=',$user->id)->first();
        switch($status){
            case 1:
                $query->facebook = $value;
                $query->save();
                return ['message' => "Basarıyla facebook adresinizi güncellediniz.",
                        'success' => true,
                        'status' => 1];
            break;
            case 2:
                $query->twitter = $value;
                $query->save();
                return ['message' => "Basarıyla twitter adresinizi güncellediniz.",
                        'success' => true,
                        'status' => 2];
            break;
            default:
                $query->instagram = $value;
                $query->save();
                return ['message' => "Basarıyla instagram adresinizi güncellediniz.",
                        'success' => true,
                        'status' => 3];
            break;    
        }      

    }

    public function getSocialMedia(Request $request){
        $model = new UserInfo;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->findorFail($user->id);
        return ['data' => $query];
    }

    public function getViewSocialMedia(Request $request){
        $person_username = $request->input('person_username');
        $model = new UserInfo;
        $userModel = new Users;
        $Users = $userModel->where('username','=',$person_username)->first();
        $query = $model->where('user_id','=',$Users->id)->first();
        return ['data' => $query];
        
    }
 
    public function getShareInfo(Request $request){
        $person_username = $request->input('person_username');
        $userModel = new Users;
        $postModel = new Posts;
        $commentModel = new Comments;
        $Users = $userModel->where('username','=',$person_username)->first();
        $queryComment = $commentModel->where('id','=',$Users->id)->get();
        $queryPost = $postModel->where('id','=',$Users->id)->get();
        $postCount = count($queryPost);
        $commentCount = count($queryComment);
        return ['commentCount' => $commentCount,
                'postCount' => $postCount];
    }

}