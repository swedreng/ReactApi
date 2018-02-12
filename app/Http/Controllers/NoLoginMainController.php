<?php
namespace App\Http\Controllers;
use Validator;
use App\Models\Users;
use App\Models\Posts;
use App\Models\Like;
use App\Models\Comments;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Http\Response;




class NoLoginMainController extends Controller {

    public function noLogin(Request $request){
        $postReq = $request->input('postReq');
        $model = new Users;
        $result = $model->get();
        return $model;
        $postCount = count($result);
       
        
        
        return ['data' => $query,
                'postCount' => $postCount];
        

    }

}