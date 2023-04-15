<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafes extends Model
{
    protected $table = 'cafes';
    protected $fillable = ['name','category_id','image'];
    const  IMAGE_FOLDER='public/cafes';

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/cafes/' . $value);
        } else {
            return asset('images/profile/no-image.png');
        }
    }

    public function CafeMenu(){
    return $this->hasMany(CafeMenu::class,'cafe_id');
    }
}
