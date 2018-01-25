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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use Illuminate\Http\Response;
use App\Http\Requests\FileUploadPostRequest; 


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
        
    public function getuserInfo($id){
        $query = Users::findOrFail($id);
        return $query;
    }
    public function mypic(Request $request){
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $model = $model->findOrFail($user->id);
        if(isset($model->pp)){
            return ['result' => $model->pp];
        }
        else{
            return ['result' => null];
        }
        
        
    }
    public function userinfoUpdate(Request $request){
        $model = new Users;
        $user_id = $request->input("user_id");
        $user = JWTAuth::parseToken()->authenticate();
        if($user_id == $user->id){
            $query = $model->findorFail($user->id);
            $result = $query->update($request->all());
            return ['message' => "Basarıyla bilgilerinizi güncellediniz.",
                    'success' => true];
        }else{
            return ['message' => "Bilgilerinizi güncellenirken bir problem oluştu.",
                    'success' => false];
        }
    }
    public function ppCreate(FileUploadPostRequest $request){
        
        $files = $request->file('files');
        $extension = $files->getClientOriginalExtension(); //jpg
        Storage::disk('public')->put($files->getClientOriginalName(), File::get($files));
        $model = new Users;
        $user = JWTAuth::parseToken()->authenticate();
        $model = $model->findOrFail($user->id);
        $model->pp = Storage::url($files->getClientOriginalName());
        $result = $model->save();

        if($result){
            return ['message' => 'Profil resminiz basarıyla güncellendi.',
                    'success' => true];
        }else{
            return ['message' => 'Profil resminiz güncellenirken bir sorun olustu.',
                    'success' => false];
        }
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