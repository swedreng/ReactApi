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
    protected $table = 'postspicture';
    protected $primaryKey = 'postpicture_id';
    protected $fillable = ['id','writing','image','created_at'];
    protected $hidden = [];
    protected $appends = [
        'IslikedPost', 
        'CommentCount',
        'CommentLast',
        'CommentBest',
        'Time'
    ];
   //protected $dates = ['created_at'];
  
    public function User() {
		  return $this->hasOne('App\Models\Users', 'id', 'id');
    }
    public function Comments() {
      return $this->hasMany('App\Models\Comments', 'postpicture_id', 'postpicture_id')->with('User');
    }
    public function Comment() {
      return $this->hasOne('App\Models\Comments', 'postpicture_id', 'postpicture_id')->with('User');
    }
  
    public function Likes() {
      return $this->hasMany('App\Models\Like', 'postpicture_id' , 'postpicture_id');
    }

    public function getImageAttribute($image){
      return env('APP_URL').$image;
    }

    public function getCommentCountAttribute(){
      return $this->Comments()->count();
    }
    public function getCommentLastAttribute($created_at){
      //$model = new Carbon();
      //$dates = "2018-02-06 08:37:38";
      //return $model->$created_at->format('d.m.y');

      return $this->Comment()->orderBy('comment_id','DESC')->take(3)->get();
    }

    public function getTimeAttribute(){
      $carbon =  Carbon::parse($this->created_at);
      return $carbon->diffForHumans();
    }
    public function getCommentBestAttribute(){
      return $this->Comment()->orderBy('like', 'desc')->get()->take(5); 
    }

    public function getIslikedPostAttribute(){
      $model = new Like();
      $user = JWTAuth::parseToken()->authenticate();
        
      $result = $model->where([['postpicture_id', '=', $this->postpicture_id],['id', '=', $user->id],['kind' , '=' , 'post']])->first();
      if($result && $result->like){
        return true;
      } else {
        return false;
      }
    }

}