<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['username','password','firstname' ,'lastname', 'email'];
    
}