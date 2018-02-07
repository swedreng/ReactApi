<?php 
namespace App\Models;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Comments extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $fillable = ['id','postpicture_id','writing'];
    protected $hidden = [];
    protected $appends = [
      'IsLikedComment',
      'Time'
    ];
    //protected $dates = ['created_at'];

    public function User() {
      return $this->hasOne('App\Models\Users', 'id', 'id');
    }
   
    public function getTimeAttribute(){
      $carbon =  Carbon::parse($this->created_at);
      return $carbon->diffForHumans();
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