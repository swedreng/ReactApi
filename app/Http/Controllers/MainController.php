<?php
namespace App\Http\Controllers;
use JWTAuth;
use Validator;
use App\Models\Users;
use App\Models\Posts;
use App\Models\BlockUser;
use App\Models\Comments;
use App\Models\BlockPost;
use App\Models\PostCategory;
use App\Http\Controllers\Controller; 
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginPostRequest; 
use App\Http\Requests\SignupPostRequest;

class MainController extends Controller {

    public function index(Request $request){
        $filter = $request->input('filter');
        $postReq = $request->input('postReq');
        $status = $request->input('status');
        $user = JWTAuth::parseToken()->authenticate();
        $blockModel = new BlockPost;
        $postCategoryModel = new PostCategory;
        $model = new Posts;
        $category_id = 1;
        $blockPost = $blockModel->where('user_id', '=' , $user->id)->get();
        $totalPost = $model->get();
        $postCount = count($totalPost) - count($blockPost);
        $query = Users::where('id','=',$user->id)->first();
        if($query->rank == 0){
            $query = $model
            ->leftJoin('block_post', function ($join) use ($user){
                $join->on('posts.post_id', '=', 'block_post.post_id')->where('block_post.user_id','=',$user->id);
            })
            ->leftJoin('block_user', function ($join) use ($user){
                $join->on('posts.id','=','block_user.block_user_id')->where('block_user.user_id','=',$user->id);
            })
            ->whereRaw('block_post.user_id IS NULL AND block_user.user_id IS NULL')->select('posts.*')->with(['User','Likes'])->where('confirmation','=',1)->orWhere('id','=',$user->id)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();   

            $userPostCount = $model
            ->leftJoin('block_post', function ($join) use ($user){
                $join->on('posts.post_id', '=', 'block_post.post_id')->where('block_post.user_id','=',$user->id);
            })
            ->leftJoin('block_user', function ($join) use ($user){
                $join->on('posts.id','=','block_user.block_user_id')->where('block_user.user_id','=',$user->id);
            })
            ->whereRaw('block_post.user_id IS NULL AND block_user.user_id IS NULL')->select('posts.*')->where('confirmation','=',1)->orWhere('id','=',$user->id)->get();
            $postCount = count($userPostCount);
            
        }else if($query->rank == 2){

            if($category_id){

                $query = $model
                ->leftJoin('post_category', function ($join) use ($category_id){
                    $join->on('posts.post_id', '=' ,'post_category.post_id');
                })
                ->leftJoin('block_post', function ($join) use ($user){
                    $join->on('posts.post_id', '=', 'block_post.post_id')->where('block_post.user_id','=',$user->id);
                })
                ->leftJoin('block_user', function ($join) use ($user){
                    $join->on('posts.id','=','block_user.block_user_id')->where('block_user.user_id','=',$user->id);
                }) 
                ->whereRaw('block_post.user_id IS NULL AND block_user.user_id IS NULL')->select('posts.*')
                ->where([['post_category.category_id', '=',$category_id],['confirmation','=',1]])->orWhere('id','=',$user->id)
                ->with(['User','Likes','PostCategory'])
                ->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();

                
            }

          

            $userPostCount = $model
            ->leftJoin('block_post', function ($join) use ($user){
                $join->on('posts.post_id', '=', 'block_post.post_id')->where('block_post.user_id','=',$user->id);
            })
            ->leftJoin('block_user', function ($join) use ($user){
                $join->on('posts.id','=','block_user.block_user_id')->where('block_user.user_id','=',$user->id);
            })
            ->whereRaw('block_post.user_id IS NULL AND block_user.user_id IS NULL')->select('posts.*')->get();
            $postCount = count($userPostCount);

        }else{
            $query = Posts::leftJoin('block_post', function ($join) use ($user){
                $join->on('posts.post_id', '=', 'block_post.post_id')->where('block_post.user_id','=',$user->id);
            })->whereRaw('block_post.user_id IS NULL')->select('posts.*')->with(['User','Likes','PostCategory'])->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        }
        return ['data' => $query,
                'postCount' => $postCount,
                'event' => $status];
    }

    public function noLogin(Request $request){
        $postReq = $request->input('postReq');
        $model = new Users;
        $result = $model->get();
        return $result;
        $postCount = count($result);
        $query = $model->with(['User','Likes'])->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        return ['data' => $query,
                'postCount' => $postCount,
                'event' => $status];
    }

    public function login(LoginPostRequest $request){
        
        $model = new Users;
        $username = $request->input('username');
        $user_info = $model->where('username', "=", $username)->first();
       
        if(isset($user_info->username)){
            $credentials = $request->all();
                try{
                    $token = JWTAuth::attempt($credentials);
                    if(!$token){
                        return  [
                            'message' => 'Bu kullanıcı adıyla şifre eşleşmiyor lütfen doğru bilgiler giriniz.',
                            'success' => false];
                    }
                   
                        return [
                            'message' => 'Basarıyla giriş yaptınız.',
                            'success' => true,
                            'token' => $token,
                            'username' => $username,
                            'role' => $user_info->rank,
                            'user_id' => $user_info->id,
                            'user_pp' => $user_info->pp];
        
                }catch (JWTException $e) {
                                
                        return [
                            'message' => 'Kullanıcı adına token olusturulamadı.',
                            'success' => false];
                }
            
        }else{
            return [
                'message' => 'Bu kullanıcı adına kayıt bulunamadı.',
                'success' => false];
        }
        
    }

    public function signup(SignupPostRequest $request){

        $model = new Users;
        $query = $model->create($request->all());
        $result = $query->findOrFail($query->id);
        if($result){
            return ['message' => 'Basarıyla kayıt oldunuz.',
                    'success' => true];
        }else{
            return ['message' => 'Kayıt olamadınız bir sorun olustu lütfen daha sonra tekrar deneyiniz.',
                    'success' => false];
        }
    }
    public function search(Request $request){
        $postReq = $request->input('postReq');
        $event = $request->input('event');
        $modelPost = new Posts;
        $modelUser = new Users; 
        $search = $request->input('search');
        $Users = $modelUser->where('firstname', 'LIKE', '%'.$search.'%')->get();
        $Posts = $modelPost->with(['User','Likes','Comments'])->where('writing' , 'LIKE', '%'.$search.'%')->where('confirmation','=',1)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        $Post = $modelPost->where('writing' , 'LIKE', '%'.$search.'%')->where('confirmation','=',1)->orderByRaw('post_id DESC')->get();
        $postCount = count($Post);
        return ['Users' => $Users,
                'data' => $Posts,
                'postCount' => $postCount,
                'event' => $event ];
    }
    
    public function LoginviewProfile(Request $request){
        $postReq = $request->input('postReq');
        $person_id = $request->input('person_id');
        $modelUser = new Users;
        $modelPost = new Posts;
        $Users = $modelUser->where('id','=',$person_id)->first();
        $Posts = $modelPost->with(['User','Likes','Comments'])->where('id','=',$person_id)->where('confirmation','=',1)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        $Post = $modelPost->where('id','=',$person_id)->where('confirmation','=',1)->get();
        $postCount = count($Post);
        return ['Users' => $Users,
                'data' => $Posts,
                'postCount' => $postCount];
    }
        
}