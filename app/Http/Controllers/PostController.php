<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Models\Posts;
use App\Models\Like;
use App\Models\ModConfirmation;
use App\Models\PostConfirmation;
use App\Models\BlockPost;
use App\Models\BlockUser;
use App\Models\ModBlockPost;
use App\Models\Content;
use App\Models\UserPostBanned;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Http\Requests\FileUploadPostRequest; 
use App\Http\Requests\UserPostShareRequest; 
use App\Http\Requests\UserPostLinkShareRequest; 
use App\Http\Requests\UserPostShareYoutubeRequest; 
use Google\Cloud\Vision\VisionClient;

class PostController extends Controller {

    public function index(Request $request){

        //
       
    }

    public function createpp(FileUploadPostRequest $request){ // +

        $model = new Posts;
        $writing = $request->input('writing');
        $files = $request->file('files');
        $user = JWTAuth::parseToken()->authenticate();    
        $query = Users::where('id','=',$user->id)->first(); 

       /* if($query->rank == 0 || $query->quality_user == 0){
        $projectId = 'plated-course-199311';
        $config = [
            'keyFile' => json_decode(Storage::disk('local')->get('test.json'), true),
            'projectId' => $projectId,
        ];
        $vision = new VisionClient($config);
        $image = $vision->image(fopen($files, 'r'), [
            'SAFE_SEARCH_DETECTION','LABEL_DETECTION'
        ],["languageHints" => 'tr']);    
        $result = $vision->annotate($image);
        print("LABELS:\n");
        foreach ($result->labels() as $label) {
            print($label->description() . PHP_EOL);
        }
        return ['message' => $result->isAdult()];
        }    */
    
        $extension = $files->getClientOriginalExtension(); //jpg
        Storage::disk('public')->put($files->getClientOriginalName(), File::get($files));
        $image = Storage::url($files->getClientOriginalName());
        
        if($query->rank == 1){
            $result = $model->create(['id' => $user->id ,'writing'=> $writing, 'image' => $image ,'confirmation' => true ,'kind' => 'picture']);
        }else if($query->rank == 2){
            $result = $model->create(['id' => $user->id ,'writing'=> $writing, 'image' => $image ,'confirmation' => true ,'kind' => 'picture']);
        }else{
            $result = $model->create(['id' => $user->id ,'writing'=> $writing, 'image' => $image ,'kind' => 'picture']);
        }

        if($result){
            $query = $model->orderBy('post_id','desc')->take(1)->first();
            $modelConf = new PostConfirmation;
            $query = $modelConf->create(['post_id' => $query->post_id,'confirmation_count' => 0]);
            $query2 = ModBlockPost::create(['post_id' => $query->post_id, 'block_count' => 0]);
            if($query && $query2){
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
        }else if($query->rank == 2){
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'kind' => 'write', 'confirmation' => true]);
        }else{
            $result = $model->create(['id' => $user->id ,'writing'=> $write,'kind' => 'write']);
        }
        
        if($result){
            $query = $model->orderBy('post_id','desc')->take(1)->first();
            $modelConf = new PostConfirmation;
            $query = ModBlockPost::create(['post_id' => $query->post_id, 'block_count' => 0]);
            $query2 = $modelConf->create(['post_id' => $query->post_id,'confirmation_count' => 0]);
            if($query && $query2){
                return ['message' => 'Profilinizde paylaşıldı.',
                        'success' => true];
            }else{
                return ['message' => 'Bir problem oluştu lütfen bize bildirin.',
                        'success' => false];
            }
            
        }
    }

    public function createlink(UserPostLinkShareRequest $request){
        $model = new Posts;
        $write = $request->input('write');
        $link = $request->input('link');
        $user = JWTAuth::parseToken()->authenticate();
        $query = Users::where('id','=',$user->id)->first();
        
        if($query->rank == 1){
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'link' =>  $link, 'kind' => 'link', 'confirmation' => true]);
        }else if($query->rank == 2){
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'link' =>  $link, 'kind' => 'link', 'confirmation' => true]);
        }else{
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'link' =>  $link, 'kind' => 'link']);
        }
        
        if($result){
            $query = $model->orderBy('post_id','desc')->take(1)->first();
            $modelConf = new PostConfirmation;
            $query = ModBlockPost::create(['post_id' => $query->post_id, 'block_count' => 0]);
            $query2 = $modelConf->create(['post_id' => $query->post_id,'confirmation_count' => 0]);
            if($query && $query2){
                return ['message' => 'Profilinizde paylaşıldı.',
                        'success' => true];
            }else{
                return ['message' => 'Bir problem oluştu lütfen bize bildirin.',
                        'success' => false];
            }
            
        }
    }
    public function createYoutubeLink(UserPostShareYoutubeRequest $request){
        $model = new Posts;
        $link = $request->input('link');
        $write = $request->input('write');
        $linkExplode = explode ("=",$link);
        $user = JWTAuth::parseToken()->authenticate();

        if($user->rank == 1){
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'youtube_link' =>  $linkExplode[1], 'kind' => 'youtube_link', 'confirmation' => true]);
        }else if($user->rank == 2){
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'youtube_link' =>  $linkExplode[1], 'kind' => 'youtube_link', 'confirmation' => true]);
        }else{
            $result = $model->create(['id' => $user->id ,'writing'=> $write, 'youtube_link' =>  $linkExplode[1], 'kind' => 'youtube_link']);
        }
        
        if($result){
            $query = $model->orderBy('post_id','desc')->take(1)->first();
            $modelConf = new PostConfirmation;
            $query = ModBlockPost::create(['post_id' => $query->post_id, 'block_count' => 0]);
            $query2 = $modelConf->create(['post_id' => $query->post_id,'confirmation_count' => 0]);
            if($query && $query2){
                return ['message' => 'Profilinizde paylaşıldı.',
                        'success' => true];
            }else{
                return ['message' => 'Bir problem oluştu lütfen bize bildirin.',
                        'success' => false];
            }
            
        }
        
    }

    private function deletePost($post_id,$block_count){

        $query = Users::where('rank','=',2)->get();
        $modCount = count($query);
        $condition = $modCount / 2;
        if($block_count >= $condition){
            $query = Posts::findOrFail($post_id);
            $query = $query->delete();
            $postCount = $modCount - 1;
            if($query){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    private function blockPostCount($post_id){
        $blockPostModel = new ModBlockPost;
        $query = $blockPostModel->where('post_id','=',$post_id)->first();
        $query->block_count = $query->block_count + 1;
        $query->save();
        $result = $this->deletePost($post_id,$query->block_count);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function blockPost(Request $request){
        $post_id = $request->input('post_id'); 
        $blockPostModel = new BlockPost;
        $user = JWTAuth::parseToken()->authenticate();
        $model = new Posts;
        switch($user->rank){
            case 0:
                $user = JWTAuth::parseToken()->authenticate();
                $query = $blockPostModel->create(['user_id'=>$user->id,'post_id'=>$post_id]);
                $query = $model->get();
                $postCount = count($query);
                return ['IsBlockPost' => true, 'postCount' => $postCount];
            break;
            case 4:
            $user = JWTAuth::parseToken()->authenticate();
            $query = $blockPostModel->create(['user_id'=>$user->id,'post_id'=>$post_id]);
            $query = $model->get();
            $postCount = count($query);
            return ['IsBlockPost' => true, 'postCount' => $postCount];
        break;
            case 2:
                $user = JWTAuth::parseToken()->authenticate();
                $query = $blockPostModel->create(['user_id'=>$user->id,'post_id'=>$post_id]);
                if($query){
                    $result = $this->blockPostCount($post_id);
                    if($result){
                        $query = $model->get();
                        $postCount = count($query);
                        return ['IsBlockPost' => true, 'postCount' => $postCount];
                    }else{
                        return ['IsBlockPost' => true, 'postCount' => $postCount];
                    }
                }
            break; 
        }
           
    }
    public function userConfirmation(Request $request){ // Düzenlenecek filtrelenecek
        $user_id = $request->input('user_id');
        $model = new Users;
        $query = $model->where('id','=',$user_id)->first();
        $query->rank = 4;
        $query->save();
    }
    public function blockUser(Request $request){
        $user_id = $request->input('user_id');
        $blockUserModel = new BlockUser;
        $UserPostBanned = new UserPostBanned;
        $model = new Posts;
        $user = JWTAuth::parseToken()->authenticate();
        switch($user->rank){
            case 0:
                $user = JWTAuth::parseToken()->authenticate();
                $query = $blockUserModel->create(['user_id'=>$user->id,'block_user_id'=>$user_id]);
                $query = $model->get();
                $postCount = count($query);
                return ['IsBlockUser' => true,'postCount' => $postCount];
            break;
            case 4:
            $user = JWTAuth::parseToken()->authenticate();
            $query = $blockUserModel->create(['user_id'=>$user->id,'block_user_id'=>$user_id]);
            $query = $model->get();
            $postCount = count($query);
            return ['IsBlockUser' => true,'postCount' => $postCount];
        break;
            case 2:
                $user = JWTAuth::parseToken()->authenticate();
                $query = $blockUserModel->create(['user_id'=>$user->id,'block_user_id'=>$user_id]);
                $query = $UserPostBanned->create(['mod_id' => $user->id,'banned_user_id' => $user_id]);
                $query = $model->get();
                $postCount = count($query);
                return ['IsBlockUser' => true,'postCount' => $postCount];
            break; 
            case 1:
                $user = JWTAuth::parseToken()->authenticate();
                $query = $blockUserModel->create(['user_id'=>$user->id,'block_user_id'=>$user_id]);
                $query = $UserPostBanned->create(['mod_id' => $user->id,'banned_user_id' => $user_id]);
                $query = $model->get();
                $postCount = count($query);
                return ['IsBlockUser' => true,'postCount' => $postCount];
        }   

    }
    public function delete(Request $request){ // düzenlenicek
       $model = new Posts;
       $post_id = $request->input('post_id');
       $user = JWTAuth::parseToken()->authenticate();
       $query = Users::where('id','=',$user->id)->first();
       
       switch($query->rank){
           case 0: 
                $queryPost = $model->where('post_id','=',$post_id)->first();
                if($query->id == $queryPost->id){
                    $query = $model->findOrFail($post_id);
                    $result = $query->delete();
                    $posts = $model->get();
                    $postCount = count($posts);
                    return ['result' => $result,
                    'postCount' => $postCount];
                }else{
                    return ['result' => false,
                    'message' => 'Size ait olmayan bir postu silmeye çalışıyorsunuz.'];
                }
           case 1:
                $query = $model->findOrFail($post_id);
                $result = $query->delete();
                $posts = $model->get();
                $postCount = count($posts);
                return ['result' => $result,
                        'postCount' => $postCount];
           break;
           case 2:
                $queryPost = $model->where('post_id','=',$post_id)->first();
                if($query->id == $queryPost->id){
                    $query = $model->findOrFail($post_id);
                    $result = $query->delete();
                    $posts = $model->get();
                    $postCount = count($posts);
                    return ['result' => $result,
                    'postCount' => $postCount];
                }else{
                    return ['result' => false,
                    'message' => 'Size ait olmayan bir postu silmeye çalışıyorsunuz.'];
                }
           break;
           case 4:
                $queryPost = $model->where('post_id','=',$post_id)->first();
                if($query->id == $queryPost->id){
                    $query = $model->findOrFail($post_id);
                    $result = $query->delete();
                    $posts = $model->get();
                    $postCount = count($posts);
                    return ['result' => $result,
                    'postCount' => $postCount];
                }else{
                    return ['result' => false,
                    'message' => 'Size ait olmayan bir postu silmeye çalışıyorsunuz.'];
                }
           break;
       }
     
    }

    private function postConfirmationModerator($post_id,$confirmation_count){
        $model = new Users;
        $modelConf = new PostConfirmation;
        $query = $model->where('rank','=', 2)->get();
        $modCount = count($query);
        $condition = $modCount / 2;
        if($confirmation_count >= $condition){
            $modelPosts = new Posts;
            $result = $modelPosts->where('post_id','=',$post_id)->first();
            $result->confirmation = true;
            $result->save();
            return true;
        }else{
            $modelPosts = new Posts;
            $result = $modelPosts->where('post_id','=',$post_id)->first();
            $result->confirmation = false;
            $result->save();
            return false;
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
                return ['postConfirmation' => false,
                        'IsRole' => 1];
                 
            }else{
                $query->confirmation = true;
                $query->save();
                return ['postConfirmation' => true,
                        'IsRole' => 1];
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
                        $postConfirmation = $this->postConfirmationModerator($post_id,$query->confirmation_count);
                        return ['IsConfirmationPost' => false,
                                'IsRole' => 2,
                                'postConfirmation' => $postConfirmation];
                    }else{
                        $query->confirmation = true;
                        $query->save();
                        $query = $postConfModel->where('post_id','=',$post_id)->first();
                        $query->confirmation_count = $query->confirmation_count + 1;
                        $query->save();
                        $postConfirmation = $this->postConfirmationModerator($post_id,$query->confirmation_count);
                        return ['IsConfirmationPost' => true,
                                'IsRole' => 2,
                                'postConfirmation' => $postConfirmation];
                    }   

                }else{
                    $query = $modModel->create(['moderator_id' => $user_id,'confirmation' => true,'post_id' => $post_id]);
                    $query = $postConfModel->where('post_id','=', $post_id)->first();
                    $query->confirmation_count = $query->confirmation_count + 1;
                    $query->save();
                    $postConfirmation = $this->postConfirmationModerator($post_id,$query->confirmation_count);
                    return ['IsConfirmationPost' => true,
                            'IsRole' => 2,
                            'postConfirmation' => $postConfirmation];
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
    public function getBestPostLogin(Request $request){
        $model = new Posts;
        $post_id = $request->input('post_id');
        $query = $model->with(['User','Likes','PostCategory'])->where('post_id','=',$post_id)->get();
        return $query;
    }
    public function createContent(Request $request){
        $model = new Content;
        $title = $request->input('title');
        $files1 = $request->file('files1');
        $files2 = $request->file('files2');
        $files3 = $request->file('files3');
        $files4 = $request->file('files4');
        $files5 = $request->file('files5');
        $files6 = $request->file('files6');
        $files7 = $request->file('files7');
        $files8 = $request->file('files8');
        $files9 = $request->file('files9');
        $files10 = $request->file('files10');
        $writing1 = $request->input('writing1');
        $writing2 = $request->input('writing2');
        $writing3 = $request->input('writing3');
        $writing4 = $request->input('writing4');
        $writing5 = $request->input('writing5');
        $writing6 = $request->input('writing6');
        $writing7 = $request->input('writing7');
        $writing8 = $request->input('writing8');
        $writing9 = $request->input('writing9');
        $writing10 = $request->input('writing10');
        $user = JWTAuth::parseToken()->authenticate();    
        $query = Users::where('id','=',$user->id)->first(); 
        if(!is_null($files1)){
            Storage::disk('public')->put($files1->getClientOriginalName(), File::get($files1));
            $image1 = Storage::url($files1->getClientOriginalName());
        }
        if(!is_null($files2)){
            Storage::disk('public')->put($files2->getClientOriginalName(), File::get($files2));
            $image2 = Storage::url($files2->getClientOriginalName());
        }else {
            $image2 = 'Doldurulmamıs';
        }
        if(!is_null($files3)){
            Storage::disk('public')->put($files3->getClientOriginalName(), File::get($files3));
            $image3 = Storage::url($files3->getClientOriginalName());
        }else{
            $image3 = 'Doldurulmamıs';
        }
        if(!is_null($files4)){
            Storage::disk('public')->put($files4->getClientOriginalName(), File::get($files4));
            $image4 = Storage::url($files4->getClientOriginalName());
        }else{
            $image4 = 'Doldurulmamıs';
        }
        if(!is_null($files5)){
            Storage::disk('public')->put($files5->getClientOriginalName(), File::get($files5));
            $image5 = Storage::url($files5->getClientOriginalName());
        }else{
            $image5 = 'Doldurulmamıs';
        }
        if(!is_null($files6)){
            Storage::disk('public')->put($files6->getClientOriginalName(), File::get($files6));
            $image6 = Storage::url($files6->getClientOriginalName());
        }else{
            $image6 = 'Doldurulmamıs';
        }
        if(!is_null($files7)){
            Storage::disk('public')->put($files7->getClientOriginalName(), File::get($files7));
            $image7 = Storage::url($files7->getClientOriginalName());
        }else{
            $image7 = 'Doldurulmamıs';
        }
        if(!is_null($files8)){
            Storage::disk('public')->put($files8->getClientOriginalName(), File::get($files8));
            $image8 = Storage::url($files8->getClientOriginalName());
        }else{
            $image8 = 'Doldurulmamıs';
        }
        if(!is_null($files9)){  
            Storage::disk('public')->put($files9->getClientOriginalName(), File::get($files9));
            $image9 = Storage::url($files9->getClientOriginalName());
        }else{
            $image9 = 'Doldurulmamıs';
        }
        if(!is_null($files10)){
            Storage::disk('public')->put($files10->getClientOriginalName(), File::get($files10));
            $image10 = Storage::url($files10->getClientOriginalName());
        }else{
            $image10 = 'Doldurulmamıs';
        }

        if(!is_null($title)){
            $slug = str_slug($title, '-');
        }
        
        if($query->rank == 1){
            $result = $model->create(['user_id' => $user->id ,'title'=> $title,
             'image1' => $image1 ,'writing1' => $writing1,
             'image2' => $image2, 'writing2' => $writing2,
             'image3' => $image3, 'writing3' => $writing3,
             'image4' => $image4, 'writing4' => $writing4,
             'image5' => $image5, 'writing5' => $writing5,
             'image6' => $image6, 'writing6' => $writing6,
             'image7' => $image7, 'writing7' => $writing7,
             'image8' => $image8, 'writing8' => $writing8,
             'image9' => $image9, 'writing9' => $writing9,
             'image10' => $image10, 'writing10' => $writing10,
             'slug' => $slug
             ]);
             if($result){
                 return ['result' => true];
             }else{
                 return ['result' => false];
             }
        }

        
    }
  
}