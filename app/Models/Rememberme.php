<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rememberme extends Model {
    protected $table = 'rememberme';
    protected $primaryKey = 'rememberme_id';
    protected $fillable = ['username','password','rememberme_token'];
    protected $hidden = [];


    
}