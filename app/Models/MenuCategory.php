<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class MenuCategory extends Model
{

    use CrudTrait;

    protected $table = 'menu_category';

    protected $fillable = ['name','restaurant_id','cat_id'];

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

    public function restaurants()
    {
        return $this->belongsTo(Restaurants::class,'restaurant_id');
    }
    public function MainCategories()
    {
         return $this->belongsTo(MainCategories::class,'cat_id');
    }

}
