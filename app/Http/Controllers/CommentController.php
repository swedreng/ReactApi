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


class CommentController extends Controller {

    public function index(Request $request){
        $post_id = $request->input('postpicture_id');
        $model = new Comments;
        $user = JWTAuth::parseToken()->authenticate();
        $id = $user->id;
        $result = $model->create(array_merge($request->all(),['id' => $id]));
        return ['result' => $result,
                'post_id' => $post_id];     

       
    }
    public function commentUpdate(Request $request){
       $post_id = $request->input('post_id');
       $model = new Posts;
       $query = $model->with(['User', 'Comments','PostLike'])->where('postpicture_id', '=', $post_id)->first();
       return $query;
 
    }

   

}