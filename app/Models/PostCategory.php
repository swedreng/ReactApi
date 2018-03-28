<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'post_category';
    protected $primaryKey = 'post_category_id';
    protected $fillable = ['post_id','category_id'];
    protected $hidden = [];
    
}