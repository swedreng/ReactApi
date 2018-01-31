<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $fillable = ['id','postpicture_id','writing'];
    protected $hidden = [];

    public function User() {
      return $this->hasOne('App\Models\Users', 'id', 'id');
    }
}