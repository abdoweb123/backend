<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAttribute extends Model
{
    protected $table = 'order_attributes';
    protected $fillable = ['order_id','order_item','body'];
    
    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }
    
    
}
