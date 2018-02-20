<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostConfirmation extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'post_confirmation';
    protected $primaryKey = 'postconfirmation_id';
    protected $fillable = ['post_id','confirmation_count'];
    protected $hidden = [];
    
}