<?php

namespace App\Models;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class CarModels extends Model
{

    use CrudTrait;

    protected $table = 'car_models';
    protected $fillable = ['name','car_factories_id'];
    protected $with = ['Carslist'];

    
    
    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

    public function CarCompany(){
        return $this->belongsTo(CarFactories::class,'car_factories_id');
    }

    public function Carslist(){
        return $this->hasMany(Carslist::class,'car_models_id');
    }


}
