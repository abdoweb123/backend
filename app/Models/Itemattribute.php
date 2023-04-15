<?php

namespace App\Models;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Itemattribute extends Model
{

    use CrudTrait;

    protected $table='attributes';
    
    protected $fillable=['name','price','item_id'];

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }
}
