<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class RequerdEquipment extends Model
{
    use CrudTrait;

    protected $table = 'requerd_equipments';
    protected $fillable = ['name','price','type'];

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }
}
