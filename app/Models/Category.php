<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $fillable = ['category_name'];
    protected $hidden = [];
    
}