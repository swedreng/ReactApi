<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'likes';
    protected $primaryKey = 'like_id';
    protected $fillable = ['id','post_id','likepost'];
    protected $hidden = [];

    public function User() {
      return $this->hasOne('App\Models\Users', 'id', 'id');
    }
}