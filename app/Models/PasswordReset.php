<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'password_reset';
    protected $primaryKey = 'password_reset_id';
    protected $fillable = ['user_id','token','email'];
    protected $hidden = [];

}