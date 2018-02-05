<?php 
namespace App\Models;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Like;

class Posts extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'postspicture';
    protected $primaryKey = 'postpicture_id';
    protected $fillable = ['id','writing','image'];
    protected $hidden = [];

    protected $appends = [
        'IslikedPost', 
        'CommentCount',
    ];
  
    public function User() {
		  return $this->hasOne('App\Models\Users', 'id', 'id');
    }
    public function Comments() {
      return $this->hasMany('App\Models\Comments', 'postpicture_id', 'postpicture_id')->with('User')->orderBy('comment_id','DESC');
    }
    public function CommentBest() {
      return $this->hasMany('App\Models\Comments', 'postpicture_id', 'postpicture_id')->with('User');
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
    public function getCommentAttribute(){
      return $this->Comments()->orderBy('comment_id','DESC');
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