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
        $user = JWTAuth::parseToken()->authenticate();
        $model->id = $user->id;
        $model->writing = $writing;
        $model->image = Storage::url($files->getClientOriginalName());
        $result = $model->save();

        if($result){
            return ['message' => 'Profilinizde paylaşıldı.',
                    'success' => true];
        }
      
    }
        
    public function getUser(Request $request){
       
    }

    public function delete($id){
       
    }

 
}