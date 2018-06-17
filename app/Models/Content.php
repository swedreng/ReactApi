<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Content extends Model {
    use SoftDeletes;
    protected $softDelete = true;
    protected $table = 'contents';
    protected $primaryKey = 'contents_id';
    protected $fillable = ['user_id','title','writing1','writing2','writing3',
                                             'writing4','writing5','writing6',
                                             'writing7','writing8','writing9',
                                             'writing10',
                                             'image1','image2','image3',
                                             'image4','image5','image6',
                                             'image7','image8','image9',
                                             'image10', 'slug'];
    protected $hidden = [];
    protected $appends = [
        'Time'
    ];
    

    public function getImage1Attribute($image1){
        if($image1 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image1;
        }
       
    }
    public function getImage2Attribute($image2){
        if($image2 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image2;
        }
        
    }
    public function getImage3Attribute($image3){
        if($image3 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image3;
        }
        
    }
    public function getImage4Attribute($image4){
        if($image4 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image4;
        }
        
    }
    public function getImage5Attribute($image5){
        if($image5 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image5;
        }
        
    }
    public function getImage6Attribute($image6){
        if($image6 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image6;
        }
    }
    public function getImage7Attribute($image7){
        if($image7 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image7;
        }
    }
    public function getImage8Attribute($image8){
        if($image8 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image8;
        }
    }
    public function getImage9Attribute($image9){
        if($image9 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image9;
        }
    }
    public function getImage10Attribute($image10){
        if($image10 == 'Doldurulmamıs'){
            return null;
        }else{
            return env('APP_URL').$image10;
        }
    }
    public function getTimeAttribute(){
        $carbon =  Carbon::parse($this->created_at);
        return $carbon->diffForHumans();
    }

}