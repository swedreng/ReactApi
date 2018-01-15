<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;

class UserController extends Controller {

    public function index(Request $request){
       
        $model = new Users;
        $username = $request->input('username');
        $password = $request->input('password');
       
        if($username && $password){
            $user_info = $model->where('username', "=", $username)->get();
            $query = $model->where('username' , '=', $username)->first();

            if($query){
                $passwordSolved = $model->hashCheck($password, $user_info[0]["password"]);
                if($passwordSolved){
                    return [
                        'message' => 'Basarıyla giriş yaptınız.',
                        'success' => true];
                }else{
                    return [
                        'message' => 'Girdiğiniz sifre yanlış lütfen tekrar deneyiniz.',
                        'success' => false];
                } 
            }else{
                return [
                    'message' => 'Girdiğiniz kullanıcı adına kayıt bulunamadı.',
                    'success' => false];
            }                  
        }    
    }

    public function create(Request $request){
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

    public function get($id){
        $query = Users::findOrFail($id);
        return $query;
    }

    public function getUser(){
        $model = new Users;
        $query = $model->get();
        return $query;
    }

    public function delete($id){
        $query = Users::findOrFail($id);
        $result = $query->delete($id);
        if($result){
            return ['sonuc' => 'Basariyla kullanıcıyı sildiniz.'];
        }else{
            return ['sonuc' => 'Kullanıcıyı silerken bir problem olustu.'];
        }
    }
}