<?php
namespace App\Http\Controllers;
use Validator;
use App\Models\NoLoginPosts;
use App\Models\NoLoginComments;
use App\Models\Comments;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class NoLoginMainController extends Controller {

    public function index(Request $request){
        $postReq = $request->input('postReq');
        $status = $request->input('status');
        $model = new NoLoginPosts;
        $result = $model->get();
        $postCount = count($result);
        $query = $model->with(['User','Likes'])->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        return ['data' => $query,
                'postCount' => $postCount,
                'event' => $status];
    }

    public function getComments(Request $request){
        $post_id = $request->input('post_id');
        $clickCount = $request->input('clickCount');
        $model = new NoLoginComments;
        $result = $model->where('post_id', '=' , $post_id)->get();
        $commentCount = count($result);
        if($commentCount - $clickCount < 0) {
            if($clickCount - $commentCount == 1) {
                $query = $model->with('User')->where('post_id', '=' , $post_id)->skip(0)->take(2)->get();
                return ['data' => $query];
            }
            if($clickCount - $commentCount == 3){
                return ['data' => false];
            }
            $query = $model->with('User')->where('post_id', '=' , $post_id)->skip(0)->take(1)->get();
            return ['data' => $query];
        }else {
            $query = $model->with('User')->where('post_id', '=' , $post_id)->skip($commentCount-$clickCount)->take(3)->get();
            return ['data' => $query];
        }
        
    }
    

}