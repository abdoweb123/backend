<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafeMenu extends Model
{
    protected $table = 'cafemenu';
    protected $fillable = ['name','price','image','description','cafe_id'];
    const  IMAGE_FOLDER='public/cafes/products';

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/cafes/products/' . $value);
        } else {
            return asset('images/profile/no-image.png');
        }
    }

    public function Cafes()
    {
        return $this->belongsTo(Cafes::class);
    }
}
