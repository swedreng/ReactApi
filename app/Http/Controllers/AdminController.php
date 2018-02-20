<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Models\Posts;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Http\Requests\FileUploadPostRequest; 

class AdminController extends Controller {

    public function index(Request $request){

        //
       
    }
     
    public function postConfirmation(Request $request){
        
        $post_id = $request->input('post_id');
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;
        $query = Users::where('id','=',$user_id)->first();     
        if($query->rank == 1){
            $query = Posts::where('post_id' , '=', $post_id)->first();
            $query->confirmation = true;
            $query->save();
            return['result' => true];
        }
    }
    public function getUser(Request $request){

        $model = new Users;
        $query = $model::paginate(7);
        return $query;
    }

    public function delete($id){
       
        $query = Users::findOrFail($id);
        $result = $query->delete($id);
        if($result){
            return ['message' => 'Basariyla kullanıcıyı sildiniz.',
                    'success' => true];
        }else{
            return ['message' => 'Kullanıcıyı silerken bir problem olustu.',
                    'success' => false];
        }
    }
 
    

 
}