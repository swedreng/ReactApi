<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModConfirmation extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'mod_confirmation';
    protected $primaryKey = 'modconfirmation_id';
    protected $fillable = ['post_id','moderator_id','confirmation'];
    protected $hidden = [];
  
}