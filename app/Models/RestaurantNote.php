<?php

namespace App\Models;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class RestaurantNote extends Model
{

    use CrudTrait;

    protected $table = 'restaurant_notes';
    protected $fillable = ['description','price'];

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

}
