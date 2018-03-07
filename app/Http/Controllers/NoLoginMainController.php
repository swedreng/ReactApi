<?php
namespace App\Http\Controllers;
use Validator;
use App\Models\NoLoginPosts;
use App\Models\NoLoginComments;
use App\Models\Users;
use App\Models\Comments;
use App\Models\Contact;
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
        $query = $model->with(['User','Likes'])->where('confirmation','=',1)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
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

    public function contact(Request $request){
        $model = new Contact;
        $choose = $request->input('choose');
        if($choose == 1){
            $choose = 'Hesabımla ilgili bir sorun';
        }else if($choose == 2){
            $choose = 'Tavsiye';
        }else if($choose == 3){
            $choose = 'Reklam';
        }else{
            $choose = 'iletisim';
        }
        $query = $model->create(['nameSurname' => $request->input('name'),'email' => $request->input('email'),'issue' => $choose,'message' => $request->input('message')]);
        if($query){
            return ['message' => 'Başarıyla mesajınız iletildi.','result'=>true];
        }else{
            return ['message' => 'Bir sorun oluştu mesajınız iletilemedi.','result' => false];
        }
    }
    public function Search(Request $request){
        $modelPost = new NoLoginPosts;
        $modelUser = new Users;
        $search = $request->input('search');
        $a = 5;
//$modelPost->where('posts', 'LIKE', '%'.$search.'%')->get();
        $Users = $modelUser->where('firstname', 'LIKE', '%'.$search.'%')->get();
        $Posts = $modelPost->leftJoin('comments', function ($join) use ($a){
            $join->on('posts.post_id', '=', 'comments.post_id');
        })
        ->with(['User','Likes','Comments'])->where('writing' , 'LIKE', '%'.$search.'%')->get();
        return ['Users' => $Users,
                'Posts' => $Posts];

                /* SELECT * FROM `posts` 
LEFT JOIN comments ON posts.post_id = comments.post_id 
WHERE writing LIKE 'rag%' */

    }

}