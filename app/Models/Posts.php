<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'postspicture';
    protected $primaryKey = 'postpicture_id';
    protected $fillable = ['id','writing','image'];
    protected $hidden = [];

    public function User() {
		  return $this->hasOne('App\Models\Users', 'id', 'id');
    }
    public function Comments() {
      return $this->hasMany('App\Models\Comments', 'postpicture_id', 'postpicture_id')->with('User');
    }
    public function getImageAttribute($image){
      return env('APP_URL').$image;
  }
   
    
}