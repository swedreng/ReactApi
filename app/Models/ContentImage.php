<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class ContentImage extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'content_image';
    protected $primaryKey = 'content_image_id';
    protected $fillable = ['contents_id','image','desc'];
    protected $hidden = [];
    protected $appends = [
        'Time'
    ];
    

    public function getImageAttribute($image){
        
        return env('APP_URL').$image;
       
    }

    public function getTimeAttribute(){
        $carbon =  Carbon::parse($this->created_at);
        return $carbon->diffForHumans();
    }

}