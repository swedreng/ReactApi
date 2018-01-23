<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'content';
    protected $primaryKey = 'content_id';
    protected $fillable = ['id','writing','image'];
    protected $hidden = [];

    public function User() {
		return $this->hasOne('App\Models\Users', 'id', 'id');
	}
    
}