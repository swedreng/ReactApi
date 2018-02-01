<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Models\Posts;
use App\Models\PostLike;
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
        
    public function getUser(Request $request){
       
    }

    public function delete($id){
       
    }

    public function postLike(Request $request){
        $post_id = $request->input('post_id');
        $model = new Posts;
        $postlikeModel = new PostLike;
        $user = JWTAuth::parseToken()->authenticate();
        $result = $postlikeModel->where([['id', '=' , $user->id],['postpicture_id', '=' , $post_id]])->first();
       
        if(!is_null($result)){
            if($result->likepost == 0){
                $result->likepost = true;
                $result->save();
                $query = $model->findOrFail($post_id);
                $query->like = $query->like + 1;
                $result = $query->save();
                return ['result' => false];
            }else{
                $result->likepost = false;
                $result->save();
                $query = $model->findOrFail($post_id);
                $query->like = $query->like - 1;
                $result = $query->save();
                return ['result' => false];
            }

        }else{
            $postlikeModel->postpicture_id = $post_id;
            $postlikeModel->id = $user->id;
            $postlikeModel->likepost = true;
            $postlikeModel->save();
            $query = $model->findOrFail($post_id);
            $query->like = $query->like + 1;
            $result = $query->save();
            return ['result' => true];
        } 
    }
}