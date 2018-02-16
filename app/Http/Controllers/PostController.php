<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Models\Posts;
use App\Models\Like;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Http\Requests\FileUploadPostRequest; 

class PostController extends Controller {

    public function index(Request $request){

        //
       
    }

    public function createpp(FileUploadPostRequest $request){ // +
        
        $model = new Posts;
        $model->kind = 'picture';
        $writing = $request->input('writing');
        $files = $request->file('files');
        $extension = $files->getClientOriginalExtension(); //jpg
        Storage::disk('public')->put($files->getClientOriginalName(), File::get($files));
        $user = JWTAuth::parseToken()->authenticate();
        $model->id = $user->id;
        $model->writing = $writing;
        $model->image = Storage::url($files->getClientOriginalName());
        $result = $model->save();

        if($result){
            return ['message' => 'Profilinizde paylaşıldı.',
                    'success' => true];
        }
      
    }
    
    public function createwp(Request $request){
        $model = new Posts;
        $model->kind = 'write';
        $write = $request->input('write');
        $user = JWTAuth::parseToken()->authenticate();
        $model->id = $user->id;
        $model->writing = $write;
        $result = $model->save();
        
        if($result){
            return ['message' => 'Profilinizde paylaşıldı.',
                    'success' => true];
        }
    }

    public function getUser(Request $request){
       
    }

    public function delete(Request $request){
       $model = new Posts;
       $post_id = $request->input('post_id');
       $query = $model->findOrFail($post_id);
       $result = $query->delete();
       $result = $model->get();
       $postCount = count($result);
       return ['result' => $result,
                'postCount' => $postCount];
    }

    public function Like(Request $request){
        $post_id = $request->input('post_id');
        $like_kind = $request->input('like_kind');
        
        $model = new Posts;
        $likeModel = new Like;
        $user = JWTAuth::parseToken()->authenticate();
        $result = $likeModel->where([['id', '=' , $user->id],['post_id', '=' , $post_id],['kind', '=' , $like_kind]])->first();
        
        if(!is_null($result)){

                if($result->like == 0){
                    $result->like = true;
                    $result->save();
                    $query = $model->findOrFail($post_id);
                    $query->like = $query->like + 1;
                    $result = $query->save();
                    return ['result' => true,
                            'likeCount' => $query->like];
                }else{
                    $result->like = false;
                    $result->save();
                    $query = $model->findOrFail($post_id);
                    $query->like = $query->like - 1;
                    $result = $query->save();
                    return ['result' => false,
                            'likeCount' => $query->like];
                }

            }else{
            $likeModel->post_id = $post_id;
            $likeModel->id = $user->id;
            $likeModel->like = true;
            $likeModel->kind = $like_kind;
            $likeModel->comment_id = 0;
            $likeModel->save();
            $query = $model->findOrFail($post_id);
            $query->like = $query->like + 1;
            $result = $query->save();
            return ['result' => true,
                    'likeCount' => $query->like];
        } 
    }

  
}