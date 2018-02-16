<?php 
namespace App\Models;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Like;
use App\Models\Comments;
use Carbon\Carbon;

class Posts extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'posts';
    protected $primaryKey = 'post_id';
    protected $fillable = ['id','writing','image','kind','created_at'];
    protected $hidden = [];
    protected $appends = [
        'IslikedPost', 
        'CommentCount',
        'CommentLast',
        'CommentBest',
        'Time',
    ];
   //protected $dates = ['created_at'];
  
    public function User() {
		  return $this->hasOne('App\Models\Users', 'id', 'id');
    }
    public function Comments() {
      return $this->hasMany('App\Models\Comments', 'post_id', 'post_id')->with('User')->orderBy('like', 'desc')->orderBy('comment_id','asc');
    }
    public function Comment() {
      return $this->hasOne('App\Models\Comments', 'post_id', 'post_id')->with('User');
    }
  
    public function Likes() {
      return $this->hasMany('App\Models\Like', 'post_id' , 'post_id');
    }

    public function getImageAttribute($image){
      return env('APP_URL').$image;
    }
    //public function CommentsLast($commentCount) {
      //return $this->hasMany('App\Models\Comments', 'postpicture_id', 'postpicture_id')->with('User')->orderBy('like', 'desc')->orderBy('comment_id','asc')->skip($commentCount-3)->take(3);
    //}
    public function getCommentCountAttribute(){
      return $this->Comments()->count();
    }
    public function getCommentLastAttribute(){
      $commentCount = $this->Comments()->count();
      return $this->Comment()->orderBy('comment_id','asc')->skip($commentCount-3)->take(3)->get();
      
    }

    public function getTimeAttribute(){
      $carbon =  Carbon::parse($this->created_at);
      return $carbon->diffForHumans();
    }
    public function getCommentBestAttribute(){
      return $this->Comment()->orderBy('like', 'desc')->orderBy('comment_id','asc')->get()->take(4); 
    }
    public function getIslikedPostAttribute(){
      $model = new Like();
      $user = JWTAuth::parseToken()->authenticate();
        
      $result = $model->where([['post_id', '=', $this->post_id],['id', '=', $user->id],['kind' , '=' , 'post']])->first();
      if($result && $result->like){
        return true;
      } else {
        return false;
      }
    }

}