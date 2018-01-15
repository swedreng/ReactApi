<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['username','password','firstname' ,'lastname', 'email'];
    protected $hidden = ['password'];

    public function setPasswordAttribute($pass){
        $this->attributes['password'] = Hash::make($pass);
    }
    public function hashCheck($req_password, $ser_password){
    	return Hash::check($req_password, $ser_password);
    }
    
}