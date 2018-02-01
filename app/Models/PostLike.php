<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostLike extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'postlike';
    protected $primaryKey = 'postlike_id';
    protected $fillable = ['id','postpicture_id','likepost'];
    protected $hidden = [];

    public function User() {
      return $this->hasOne('App\Models\Users', 'id', 'id');
    }
}