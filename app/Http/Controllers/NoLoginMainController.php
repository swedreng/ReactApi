<?php
namespace App\Http\Controllers;
use Validator;
use App\Models\NoLoginPosts;
use App\Models\NoLoginComments;
use App\Models\Posts;
use App\Models\Users;
use App\Models\Comments;
use App\Models\Contact;
use App\Models\PasswordReset;
use App\Models\PostCategory;
use App\Models\Rememberme;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Mail;


class NoLoginMainController extends Controller {

    public function index(Request $request){
        $filter = $request->input('filter');
        $postReq = $request->input('postReq');
        $status = $request->input('status');
        $model = new NoLoginPosts;
        if(!is_null($filter)){
            $query = $model
            ->leftJoin('post_category', function ($join) use ($filter){
                $join->on('posts.post_id', '=' ,'post_category.post_id');
            })
            ->select('posts.*')
            ->where([['post_category.category_id', '=',$filter],['confirmation','=',1]])
            ->with(['User','Likes','PostCategory'])
            ->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();

            $Count = $model
            ->leftJoin('post_category', function ($join) use ($filter){
                $join->on('posts.post_id', '=' ,'post_category.post_id');
            })
            ->select('posts.*')
            ->where([['post_category.category_id', '=',$filter],['confirmation','=',1]])
            ->with(['User','Likes'])
            ->orderByRaw('post_id DESC')->get();
            $postCount = count($Count);

            return ['data' => $query,
                    'postCount' => $postCount,
                    'event' => $status];
        }else{
            $query = $model->where('confirmation','=',1)->with(['User','Likes'])->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
            $Count = $model->where('confirmation','=',1)->with(['User','Likes'])->orderByRaw('post_id DESC')->get();
            $postCount = count($Count);
            return ['data' => $query,
                    'postCount' => $postCount,
                    'event' => $status];
        }
      
    }

    public function getComments(Request $request){
        $post_id = $request->input('post_id');
        $clickCount = $request->input('clickCount');
        $model = new NoLoginComments;
        $result = $model->where('post_id', '=' , $post_id)->get();
        $commentCount = count($result);
        if($commentCount - $clickCount < 0) {
            if($clickCount - $commentCount == 1) {
                $query = $model->with('User')->where('post_id', '=' , $post_id)->skip(0)->take(2)->get();
                return ['data' => $query];
            }
            if($clickCount - $commentCount == 3){
                return ['data' => false];
            }
            $query = $model->with('User')->where('post_id', '=' , $post_id)->skip(0)->take(1)->get();
            return ['data' => $query];
        }else {
            $query = $model->with('User')->where('post_id', '=' , $post_id)->skip($commentCount-$clickCount)->take(3)->get();
            return ['data' => $query];
        }
        
    }

    public function contact(Request $request){
        $model = new Contact;
        $choose = $request->input('choose');
        if($choose == 1){
            $choose = 'Hesabımla ilgili bir sorun';
        }else if($choose == 2){
            $choose = 'Tavsiye';
        }else if($choose == 3){
            $choose = 'Reklam';
        }else{
            $choose = 'iletisim';
        }
        $query = $model->create(['nameSurname' => $request->input('name'),'email' => $request->input('email'),'issue' => $choose,'message' => $request->input('message')]);
        if($query){
            return ['message' => 'Başarıyla mesajınız iletildi.','result'=>true];
        }else{
            return ['message' => 'Bir sorun oluştu mesajınız iletilemedi.','result' => false];
        }
    }
    public function Search(Request $request){
        $postReq = $request->input('postReq');
        $event = $request->input('event');
        $modelPost = new NoLoginPosts;
        $search = $request->input('search');
        $Posts = $modelPost->with(['User','Likes','Comments'])->where('writing' , 'LIKE', '%'.$search.'%')->where('confirmation','=',1)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        $Post = $modelPost->where('writing' , 'LIKE', '%'.$search.'%')->where('confirmation','=',1)->orderByRaw('post_id DESC')->get();
        $postCount = count($Post);
        return ['data' => $Posts,
                'postCount' => $postCount,
                'event' => $event ];
    }
    public function searchPerson(Request $request){
        $postReq = $request->input('postReq');
        $event = $request->input('event');
        $modelUser = new Users; 
        $search = $request->input('search');
        $Users = $modelUser->where('firstname', 'LIKE', '%'.$search.'%')->skip($postReq)->take(3)->get();
        $user = $modelUser->where('firstname', 'LIKE', '%'.$search.'%')->get();
        $userCount = count($user);
        return [
            'Users' => $Users,
            'userCount' => $userCount];
    }
    
    public function viewProfile(Request $request){
        $postReq = $request->input('postReq');
        $person_username = $request->input('person_username');
        $modelUser = new Users;
        $modelPost = new NoLoginPosts;
        $modelComment = new NoLoginComments;
        $Users = $modelUser->where('username','=',$person_username)->first();
        $Posts = $modelPost->with(['User','Likes','Comments'])->where('id','=',$Users->id)->where('confirmation','=',1)->orderByRaw('post_id DESC')->skip($postReq)->take(3)->get();
        $Post = $modelPost->where('id','=',$Users->id)->where('confirmation','=',1)->get();
        $comments = $modelComment->where('id','=',$Users->id)->get();
        $commentCount = count($comments);
        $postCount = count($Post);
        return ['Users' => $Users,
                'data' => $Posts,
                'postCount' => $postCount,
                'commentCount' => $commentCount,
                'username' => $Users->username];
    }

    public function passwordReset(Request $request){
        $title = 'Şifre Sıfırla';
        $content = 'Merhaba kullanıcı bu mesaj Opanc.com tarafından size gönderilen şifre güncelleme mesajıdır.';
        $subject = 'Opanc.com Şifre Sıfırlama';
        $email = $request->input('email');
        $userModel = new Users;
        $passwordResetModel = new PasswordReset;
        $query = $userModel->where('email','=',$email)->first();
        if($query){
             
            $status = $passwordResetModel->where('email','=',$email)->first();
            if($status){
                return ['message' => 'Bu işlemi zaten gerçekleştirdiniz, lütfen email adresinize yolladığımız linkten devam ediniz..'];
            }else{
                $token = str_random(60);
                $result = $passwordResetModel->create(['user_id' => $query->id, 'token' => $token, 'email' => $query->email]);
                Mail::send('emails.send', ['title' => $title, 'content' => $content, 'token' => $token] , function ($message) use ($email)
                {
                    $message->from('opanc.info@gmail.com','Opanc.com');
                    $message->to($email)->subject('Şifre Sıfırlama');     
        
                });
              
                return ['message' => 'Mail adresinize yolladığımız linkle şifrenizi güncelleyebilirsiniz..'];
            }
           
        }else{
            return ['message' => 'Sistemde bu emaile kayıtlı bir kullanıcı bulunmamaktadır, lütfen kontrol edip tekrar deneyiniz..'];
        }
       
    }

    public function passwordUpdate(Request $request){
        $token = $request->input('token');
        $newPassword = $request->input('password');
        $passwordResetModel = new PasswordReset;
        $userModel = new Users;
        $query = $passwordResetModel->where('token','=',$token)->first();
        
        if(!is_null($query)){
            
            $result = $userModel->where('id','=',$query->user_id)->first();
            $result->password = $newPassword;
            $result->save();
            if($result){
                $query = $passwordResetModel->findOrFail($query->password_reset_id);
                $query->delete();
                return ['message' => 'Başarıyla şifrenizi sıfırladınız, yeniden giriş yapabilirsiniz..'];
            }
            
        }else{
            return ['message' => 'Bu şifre sıfırlama linki artık kullanılmıyor, lütfen şifre sıfırlama işlemi için yeni link alınız.'];
        }
    }


    public function bestPostToday(Request $request){
        $model = new NoLoginPosts;
        $query = $model->with(['User','Likes','PostCategory'])->whereRaw('date(posts.created_at) = date(now())')->where('confirmation','=',1)->orderBy('like', 'DESC')->get()->take(10);
        return $query;
    }

    public function bestPost(Request $request){
        $model = new NoLoginPosts;
        $post_id = $request->input('post_id');
        $query = $model->with(['User','Likes','PostCategory'])->where('post_id','=',$post_id)->get();
        return $query;
    }
    public function topBestPost(Request $request){
        $postReq = $request->input('value');
        $model = new NoLoginPosts;
        $query = $model->with(['User','Likes','PostCategory'])->whereRaw('date(posts.created_at) = date(now())')->where('confirmation','=',1)->orderBy('like', 'DESC')->skip($postReq)->take(3)->get();
        $posts = $model->whereRaw('date(posts.created_at) = date(now())')->where('confirmation','=',1)->get();
        $postCount = count($posts);
        return ['data' => $query,
                'postCount' => $postCount];
    }
    public function rememberMe(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');
        $model = new Rememberme;
        $token = str_random(60);
        $query = $model->create(['username'=>$username,'password'=>$password,'rememberme_token' =>$token]);
        $id = $query->rememberme_id;
        $data = $model->findOrFail($id);
        return ['success' => true,
                'token' => $token,
                'data' => $data];

    }
    public function getRememberMe(Request $request){
        $token = $request->input('token');
        $model = new Rememberme;
        if($token){
            $query = $model->where('rememberme_token', '=' , $token)->first();
            if(!is_null($query)){
                return ['data' => $query,
                        'success' => true];
            }else{
                return ['success' => false];
            }
        }else{
            return ['success' => false];
        }
      
        
    }
    public function forgetMe(Request $request){
        $token = $request->input('token');
        $model = new RememberMe;
        $query = $model->where('rememberme_token','=',$token)->first();
        $query = $model->findOrFail($query->rememberme_id);
        $result = $query->delete();
        return ['success' => true,
                'data' => null];
    }

    public function allPosts(Request $request){
        $page = $request->input('page');
        $model = new NoLoginPosts;
        $query = $model->with(['User'])->where('confirmation','=',1)->orderBy('post_id','desc')->take(300)->get();
        return ['data' => $query];
    }
}