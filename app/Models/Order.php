<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;


class Order extends Model
{
    use CrudTrait;

    protected $table='orders';
    protected $with=['OrderItems'];

    protected $fillable = ['username','user_id','location','lat',
    'lng','comment','address','phone','total','status','attribute_body','additional','attribute_body_two','attribute_body_three'];
    
    // use CrudTrait;


    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

    public function OrderItems(){
        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function Trip(){
        return $this->belongsTo(Trip::class,'order_id');
    }

}
