<?php
namespace App\Http\Controllers;
use JWTAuth;
use Validator;
use App\Models\Users;
use App\Http\Controllers\Controller;  
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Http\Requests\SignupPostRequest;
use Illuminate\Http\Validation\ValidEmail;

class UserController extends Controller {

    public function index(Request $request){
       
        $model = new Users;
        $username = $request->input('username');
        $password = $request->input('password');
       
        if($username && $password){
            
            $query = $model->where('username' , '=', $username)->first();
            $user_info = $model->where('username', "=", $username)->get();

            if($query){
                        $credentials = $request->all();
                    try{
                        $token = JWTAuth::attempt($credentials);
                        if(!$token){
                            return  [
                                'message' => 'Sistemle ilgili bir problem var lütfen daha sonra tekrar deneyiniz.',
                                'success' => false];
                        }

                        return [
                            'message' => 'Basarıyla giriş yaptınız.',
                            'success' => true,
                            'token' => $token,
                            'username' => $username,
                            'role' => $user_info[0]["rank"],
                            'user_id' => $user_info[0]["id"]];

                    }catch (JWTException $e) {
                        // something went wrong whilst attempting to encode the token
                        return [
                            'message' => 'Kullanıcı adına token olusturulamadı.',
                            'success' => false];
                    }
                    return [
                            'message' => 'Sistemle ilgili bir sorun var lütfen daha sonra tekrar deneyiniz.',
                            'success' => false];
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

    public function create(SignupPostRequest $request){

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

    public function getUser(Request $request){
        $model = new Users;
        $query = $model->get();
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