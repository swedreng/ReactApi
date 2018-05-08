<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockPost extends Model {
    protected $table = 'block_post';
    protected $primaryKey = 'block_id';
    protected $fillable = ['user_id','post_id'];
    protected $hidden = [];

}