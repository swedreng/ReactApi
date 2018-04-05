<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PostCategory;

class Category extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $fillable = ['category_name'];
    protected $hidden = [];
    protected $appends = [
        'PostCount', 
    ];
    public function CategoryPostCount(){
        return $this->hasOne('App\Models\PostCategory', 'category_id', 'category_id');
    }
    public function getPostCountAttribute(){
        return $this->CategoryPostCount()->count();
    }
    
}