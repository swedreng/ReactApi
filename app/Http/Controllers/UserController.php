<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;

class UserController extends Controller {

    public function index(Request $request){

        
    }

    public function create(Request $request){
        $model = new Users;
        $query = $model->create($request->all());
        $result = $query->findOrFail($query->id);
        if($result){
            return ['sonuc' => 'basariyla kayit yaptınız.'];
        }else{
            return ['sonuc' => 'bir problem olustu.'];
        }
    }
    public function get($id){
        $query = Users::findOrFail($id);
        return $query;
    }
    public function delete($id){
        $query = Users::findOrFail($id);
        $result = $query->delete($id);
        if($result){
            return ['sonuc' => 'basariyla kullanıcıyı sildiniz.'];
        }else{
            return ['sonuc' => 'kullanıcıyı silerken bir problem olustu.'];
        }
    }
}