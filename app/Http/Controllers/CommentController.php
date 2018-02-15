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
        $openCommentCount = $request->input('commentCount');
        $model = new Comments;
        $user = JWTAuth::parseToken()->authenticate();
        $id = $user->id;
        $result = $model->create(array_merge($request->all(),['id' => $id]));
        $result = $model->where('postpicture_id', '=' , $post_id)->get();
        $commentCount = count($result);
        $query = $model->with('User')->where('postpicture_id', '=' , $post_id)->skip($commentCount-1)->take(1)->get();
        return ['data' => $query,
                'commentCount' => $commentCount];
           
    }

    public function delete(Request $request){
        $model = new Comments;
        $comment_id = $request->input('comment_id');
        $user = JWTAuth::parseToken()->authenticate();
        $id = $user->id;
        $post_id = $request->input('post_id');
        $status = $model->where([['id','=', $id],['comment_id','=', $comment_id],['postpicture_id','=', $post_id]]);
        if($status){
            $query = $model->findOrFail($comment_id);
            $result = $query->delete();
            $result = $model->where('postpicture_id','=',$post_id)->get();
            $commentCount = count($result);
            return ['result' => $result,
                     'commentCount' => $commentCount];
        }else{
            return ['result' => false];
        }
        
    } 

    public function update(Request $request){
        $model = new Comments;
        $comment_id = $request->input('comment_id');
        $post_id = $request->input('post_id');
        $comment = $request->input('comment');
        $user = JWTAuth::parseToken()->authenticate();
        $id = $user->id;
        $status = $model->where([['id','=', $id],['comment_id','=', $comment_id],['postpicture_id','=', $post_id]])->get();
        if($status){
            $query = $model->findOrFail($comment_id);
            $query->writing = $comment;
            $result = $query->save();
            return ['result' => $result];
        }else{
            return ['result' => false];
        }
        
    }

    public function commentUpdate(Request $request){
       $post_id = $request->input('post_id');
       $model = new Posts;
       $query = $model->with(['User', 'Comments','Likes'])->where('postpicture_id', '=', $post_id)->first();
       return $query;
 
    }

    public function commentLastUpdate(Request $request){
        $post_id = $request->input('post_id');
        $commentCount = $request->input('commentCount');
        $model = new Comments;
        $query = $model->with('User')->where('postpicture_id', '=' , $post_id)->orderBy('like','desc')->orderBy('comment_id','asc')->skip(0)->take($commentCount)->get();
        return $query;
  
     }
    public function getComment(Request $request){
        $post_id = $request->input('post_id');
        $clickCount = $request->input('clickCount');
        $model = new Comments;
        $result = $model->where('postpicture_id', '=' , $post_id)->get();
        $commentCount = count($result);
        if($commentCount - $clickCount < 0) {
            if($clickCount - $commentCount == 1) {
                $query = $model->with('User')->where('postpicture_id', '=' , $post_id)->skip(0)->take(2)->get();
                return ['data' => $query];
            }
            if($clickCount - $commentCount == 3){
                return ['data' => false];
            }
            $query = $model->with('User')->where('postpicture_id', '=' , $post_id)->skip(0)->take(1)->get();
            return ['data' => $query];
        }else {
            $query = $model->with('User')->where('postpicture_id', '=' , $post_id)->skip($commentCount-$clickCount)->take(3)->get();
            return ['data' => $query];
        }
        
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
                    return ['result' => true,
                            'likeCount' => $query->like];
                }else{
                    $result->like = false;
                    $result->save();
                    $query = $model->findOrFail($comment_id);
                    $query->like = $query->like - 1;
                    $result = $query->save();
                    return ['result' => false,
                            'likeCount' => $query->like];
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
            return ['result' => true,
                    'likeCount' => $query->like];
        } 
    }

   
}