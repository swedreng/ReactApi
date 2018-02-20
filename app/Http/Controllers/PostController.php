<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Models\Posts;
use App\Models\Like;
use App\Models\ModConfirmation;
use App\Models\PostConfirmation;
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
        $image = Storage::url($files->getClientOriginalName());
        $user = JWTAuth::parseToken()->authenticate();
        $query = Users::where('id','=',$user->id)->first();
        if($query->rank == 1){
            $result = $model->create(['id' => $user->id ,'writing'=> $writing, 'image' => $image ,'confirmation' => true ,'kind' => 'picture']);
        }else{
            $result = $model->create(['id' => $user->id ,'writing'=> $writing, 'image' => $image ,'kind' => 'picture']);
        }

        if($result){
            $query = $model->orderBy('post_id','desc')->take(1)->first();
            $modelConf = new PostConfirmation;
            $query = $modelConf->create(['post_id' => $query->post_id,'confirmation_count' => 0]);
            if($query){
                return ['message' => 'Profilinizde paylaşıldı.',
                'success' => true];
            }else{
                return ['message' => 'Bir problem oluştu lütfen bize bildirin.',
                'success' => false];
            } 
        }
    }
    
    public function createwp(Request $request){
        $model = new Posts;
        $write = $request->input('write');
        $user = JWTAuth::parseToken()->authenticate();
        $query = Users::where('id','=',$user->id)->first();
        
        if($query->rank == 1){
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'kind' => 'write', 'confirmation' => true]);
        }else{
            $result = $model->create(['id' => $user->id ,'writing'=> $write,'kind' => 'write']);
        }
        if($result){
            $query = $model->orderBy('post_id','desc')->take(1)->first();
            $modelConf = new PostConfirmation;
            $query = $modelConf->create(['post_id' => $query->post_id,'confirmation_count' => 0]);
            if($query){
                return ['message' => 'Profilinizde paylaşıldı.',
                'success' => true];
            }else{
                return ['message' => 'Bir problem oluştu lütfen bize bildirin.',
                'success' => false];
            }
            
        }
    }

    public function getUser(Request $request){
       //
    }

    public function delete(Request $request){ // düzenlenicek
       $model = new Posts;
       $post_id = $request->input('post_id');
       $query = $model->findOrFail($post_id);
       $result = $query->delete();
       $result = $model->get();
       $postCount = count($result);
       return ['result' => $result,
                'postCount' => $postCount];
    }
    public function postConfirmationUpdate(Request $request){
        $post_id = $request->input('post_id');
        $model = new Users;
        $modelConf = new PostConfirmation;
        $query = $model->where('rank','=', 2)->get();
        $modCount = count($query);
        $result = $modelConf->where('post_id','=',$post_id)->first();
        $confirmation_count = $result->confirmation_count;
        $condition = $modCount / 2;
        if($confirmation_count >= $condition){
            $modelPosts = new Posts;
            $result = $modelPosts->where('post_id','=',$post_id)->first();
            $result->confirmation = true;
            $result->save();
            return ['result' => true];
        }else{
            $modelPosts = new Posts;
            $result = $modelPosts->where('post_id','=',$post_id)->first();
            $result->confirmation = false;
            $result->save();
            return ['result' => false];
        }

    }
    public function postConfirmation(Request $request){

        $post_id = $request->input('post_id');
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;
        $query = Users::where('id','=',$user_id)->first();
        $modModel = new ModConfirmation;
        $postConfModel = new PostConfirmation;

        switch($query->rank){
            case 1: 
            $model = new Posts;
            $query = $model->where('post_id', '=' , $post_id)->first();
            
            if($query->confirmation){
                $query->confirmation = false;
                $query->save();  
                return ['result' => false];
                
            }else{
                $query->confirmation = true;
                $query->save();
                return ['result' => true];
            }
            break;
            case 2:
            $query = $modModel->where([['moderator_id','=', $user_id],['post_id','=',$post_id]])->first();
                if(!is_null($query)){
                    if($query->confirmation){
                        $query->confirmation = false;
                        $query->save();
                        $query = $postConfModel->where('post_id','=',$post_id)->first();
                        $query->confirmation_count = $query->confirmation_count - 1;
                        $query->save();
                        return ['result' => false,
                                'confirmation_count' => $query->confirmation_count];
                    }else{
                        $query->confirmation = true;
                        $query->save();
                        $query = $postConfModel->where('post_id','=',$post_id)->first();
                        $query->confirmation_count = $query->confirmation_count + 1;
                        $query->save();
                        return ['result' => true,
                                'confirmation_count' => $query->confirmation_count];
                    }   

                }else{
                    $query = $modModel->create(['moderator_id' => $user_id,'confirmation' => true,'post_id' => $post_id]);
                    $query = $postConfModel->where('post_id','=', $post_id)->first();
                    $query->confirmation_count = $query->confirmation_count + 1;
                    $query->save();
                    return ['result' => true,
                            'confirmation_count' => $query->confirmation_count];
                }
            break;
            
        }
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