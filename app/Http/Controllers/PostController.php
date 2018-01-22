<?php
namespace App\Http\Controllers;
use App\Models\Posts;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request\FileUploadPostRequest;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;


class PostController extends Controller {

    public function index(Request $request){
        
       
    }

    public function create(FileUploadPostRequest $request){
      
        $writing = $request->input('writing');
        $id = $request->input('id');
        $files = $request->file('files');
        $extension = $files->getClientOriginalExtension(); //jpg
        Storage::disk('public')->put($files->getClientOriginalName(), File::get($files));
        $model = new Posts;
        $model->id = $id;
        $model->writing = $writing;
        $model->image = Storage::url($files->getClientOriginalName());
        $result = $model->save();

        if($result){
            return ['message' => 'basarılı.',
                    'success' => true];
        }
      
    }
        
    public function get($id){
        
    }

    public function getUser(Request $request){
       
    }

    public function delete($id){
       
    }

 
}