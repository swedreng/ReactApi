<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockPost extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'block_post';
    protected $primaryKey = 'block_id';
    protected $fillable = ['post_id','block_count'];
    protected $hidden = [];

  
}