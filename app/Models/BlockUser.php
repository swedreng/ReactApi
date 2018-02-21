<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockUser extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'block_user';
    protected $primaryKey = 'block_id';
    protected $fillable = ['id','post_id','block_user_id','block_status'];
    protected $hidden = [];

  
}