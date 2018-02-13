<?php 
namespace App\Models;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class NoLoginComments extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $fillable = ['id','postpicture_id','writing'];
    protected $hidden = [];
    protected $appends = [
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

}