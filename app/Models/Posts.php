<?php 
namespace App\Models;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\PostLike;

class Posts extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'postspicture';
    protected $primaryKey = 'postpicture_id';
    protected $fillable = ['id','writing','image'];
    protected $hidden = [];

    protected $appends = [
        'Isliked', 
        'CommentCount'
    ];
  
    public function User() {
		  return $this->hasOne('App\Models\Users', 'id', 'id');
    }
    public function Comments() {
      return $this->hasMany('App\Models\Comments', 'postpicture_id', 'postpicture_id')->with('User')->orderByRaw('comment_id DESC');
    }
    public function PostLike() {
      return $this->hasMany('App\Models\PostLike', 'postpicture_id' , 'postpicture_id');
    }
    public function getImageAttribute($image){
      return env('APP_URL').$image;
    }
   
    public function getCommentCountAttribute(){
      return $this->Comments()->count();
    }

    public function getIslikedAttribute(){
      $model = new PostLike();
      $user = JWTAuth::parseToken()->authenticate();
        
      $result = $model->where([['postpicture_id', '=', $this->postpicture_id],['id', '=', $user->id]])->first();

      if($result && $result->likepost){
        return true;
      } else {
        return false;
      }
    }
    
}