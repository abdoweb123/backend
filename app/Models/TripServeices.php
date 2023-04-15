<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class TripServeices extends Model
{
    use CrudTrait;



    protected $table = 'trip_ service';
    protected $fillable = ['trip_id','service_name','service_id','service_image','service_count','car_type','required_equipment','price_method','note','number_technicians','number_workers','cars_count','price_method_from','price_method_to'];


    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }
}
