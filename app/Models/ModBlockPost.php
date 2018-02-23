<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModBlockPost extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'mod_block_post';
    protected $primaryKey = 'mod_block_id';
    protected $fillable = ['post_id','block_count'];
    protected $hidden = [];
    
}