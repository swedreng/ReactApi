<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockUser extends Model {
    protected $table = 'block_user';
    protected $primaryKey = 'block_id';
    protected $fillable = ['user_id','block_user_id'];
    protected $hidden = [];
    
}