<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPostBanned extends Model {
    protected $table = 'user_post_banned';
    protected $primaryKey = 'user_post_banned_id';
    protected $fillable = ['mod_id','banned_user_id'];
    protected $hidden = [];
    
}