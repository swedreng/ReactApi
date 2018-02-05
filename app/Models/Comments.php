<?php 
namespace App\Models;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $fillable = ['id','postpicture_id','writing'];
    protected $hidden = [];
    protected $appends = [
      'IsLikedComment',
      
    ];

    public function User() {
      return $this->hasOne('App\Models\Users', 'id', 'id');
    }

    public function getIsLikedCommentAttribute(){
      $model = new Like();
      $user = JWTAuth::parseToken()->authenticate();
        
      $result = $model->where([['comment_id', '=', $this->comment_id],['id', '=', $user->id],['kind' , '=' , 'comment']])->first();
      if($result && $result->like){
        return true;
      } else {
        return false;
      }
    }
}