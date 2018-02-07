<?php
namespace App\Http\Controllers;
use JWTAuth;
use Validator;
use App\Models\Users;
use App\Models\Posts;
use App\Models\Like;
use App\Models\Comments;
use App\Http\Controllers\Controller; 
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CommentController extends Controller {

    public function index(Request $request){
        $post_id = $request->input('postpicture_id');
        $model = new Comments;
        $user = JWTAuth::parseToken()->authenticate();
        $id = $user->id;
        $result = $model->create(array_merge($request->all(),['id' => $id]));
        $query = $model->with('User')->where('postpicture_id', '=' , $post_id)->orderBy('comment_id','ASC')->get();
        return $query;
           
  
    }

    public function commentUpdate(Request $request){
       $post_id = $request->input('post_id');
       $model = new Posts;
       $query = $model->with(['User', 'Comments','Likes'])->where('postpicture_id', '=', $post_id)->first();
       return $query;
 
    }
    public function getComment(Request $request){
        $value = $request->input('value');
        $post_id = $request->input('post_id');
        $model = new Comments;
        $query = $model->with('User')->where('postpicture_id', '=' , $post_id)->orderBy('comment_id','ASC')->take($value)->get();
        return $query;
    }
    
    public function Like(Request $request){
        $post_id = $request->input('post_id');
        $like_kind = $request->input('like_kind');
        $comment_id = $request->input('comment_id');
        $model = new Comments ;
        $likeModel = new Like;
        $user = JWTAuth::parseToken()->authenticate();
        $result = $likeModel->where([['id', '=' , $user->id],['comment_id', '=' , $comment_id],['kind', '=' , $like_kind]])->first();
        
        if(!is_null($result)){

                if($result->like == 0){
                    $result->like = true;
                    $result->save();
                    $query = $model->findOrFail($comment_id);
                    $query->like = $query->like + 1;
                    $result = $query->save();
                    return ['result' => false];
                }else{
                    $result->like = false;
                    $result->save();
                    $query = $model->findOrFail($comment_id);
                    $query->like = $query->like - 1;
                    $result = $query->save();
                    return ['result' => false];
                }
            
        }else{
            $likeModel->postpicture_id = $post_id;
            $likeModel->id = $user->id;
            $likeModel->like = true;
            $likeModel->kind = $like_kind;
            $likeModel->comment_id = $comment_id;
            $likeModel->save();
            $query = $model->findOrFail($comment_id);
            $query->like = $query->like + 1;
            $result = $query->save();
            return ['result' => true];
        } 
    }

   
}