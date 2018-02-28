<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'contact';
    protected $primaryKey = 'contact_id';
    protected $fillable = ['nameSurname','email','issue','message'];
    protected $hidden = [];
    
}