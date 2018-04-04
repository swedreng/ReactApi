<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'user_info';
    protected $primaryKey = 'user_info_id';
    protected $fillable = ['user_id','facebook','twitter','instagram'];
    protected $hidden = [];
    
}