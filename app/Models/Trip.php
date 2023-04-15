<?php

namespace App\Models;

use App\Models\TripServeices;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Trip extends Model
{
    use CrudTrait;

    protected $table = 'trips';



    protected $fillable = ['client_id','driver_id','from_lat','address_from','address_to','ride_type','from_lng','to_lat','to_lng','payment_method','total'];

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }


    public function Trip_Furniture()
    {
        return $this->hasMany(TripServeices::class,'trip_id');
    }

    public function Trip_RoadService()
    {
        return $this->hasMany(TripServeices::class,'trip_id');
    }
    public function Trip_Truck()
    {
        return $this->hasMany(TripServeices::class,'trip_id');
    }
    public function Trip_PrivteCars()
    {
        return $this->hasMany(TripServeices::class,'trip_id');
    }
    public function Trip_Monthly()
    {
        return $this->hasMany(Subscription::class,'trip_id');
    }
}
