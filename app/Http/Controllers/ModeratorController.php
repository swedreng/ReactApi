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
use App\Models\UserPostBanned;
use App\Models\Category;
use App\Models\PostCategory;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Http\Requests\FileUploadPostRequest; 
use Google\Cloud\Vision\VisionClient;

class ModeratorController extends Controller {

    public function setCategory(Request $request){
        $model = new PostCategory;
        $post_id = $request->input('post_id');
        $category_id = $request->input('category_id');
        $query = $model->where([['post_id','=',$post_id],['category_id','=',$category_id]])->first();
        
        if(!is_null($query)){
            $query->delete();
            $categories = $model->where('post_id','=',$post_id)->get();
                return ['status' => false,'post_categories' => $categories];
        }else{
            $result = $model->where([['post_id','=',$post_id],['category_id','=',$category_id]])->withTrashed()->first();
            if(!is_null($result)){
                $result->restore();
            }else{
                $result = $model->create($request->all());
            }
            $categories = $model->where('post_id','=',$post_id)->get();
            return ['status' => true,'post_categories' => $categories];

        }
    }
    public function getCategory(Request $request){
        $model = new Category;
        $query = $model->get();
        return $query;
    }
}