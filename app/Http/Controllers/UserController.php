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
use App\Http\Requests\UserUpdate; 

class UserController extends Controller {

    public function index(Request $request){
       
        
    }

    public function get(){ // +
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
    
    public function update(UserUpdate $request){ // +
        
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $query = $model->findorFail($user->id);
        $result = $query->update($request->all());
            return ['message' => "Basarıyla bilgilerinizi güncellediniz.",
                    'success' => true];  
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

    public function ppdelete(Request $request){
       // yapılacak.
    }

   
 
}