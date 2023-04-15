<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    use CrudTrait;



    protected $table = 'subscriptions';
    protected $fillable = ['user_id','driver_id','from_date','to_date','going_coming','from_address','to_address',
    'from_lat','from_lng','to_lat','to_lng','from_time','to_time','driver_spacliy_id','working_days'];

    protected $casts = [
        'working_days' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

    public function DriverSpacliy(){
        return $this->belongsTo(DriversSpecialty::class);
    }


}
