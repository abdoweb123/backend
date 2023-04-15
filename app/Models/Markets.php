<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Markets extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    protected $table = 'markets';
    protected $fillable = ['name','image','description','address','delivery_price','country','government','district','lat','lng'];
    const  IMAGE_FOLDER='public/markets';

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('storage/markets/' . $value);
        } else {
            return asset('images/profile/no-image.png');
        }
    }

    public function scopeWithDistance($query, $lat, $lng)
    {
        $lat = floatval($lat);
        $lng = floatval($lng);
        if (!empty($lat) || !empty($lng)) {
            return $query->selectRaw(
                "*,round(( '6371000' * acos( cos( radians('$lat') ) * cos( radians( lat ) )
                 * cos( radians( lng ) - radians('$lng') ) + sin( radians('$lat') )
                  * sin( radians( lat ) ) ) ),2) AS distance"
            )->orderBy('distance', 'asc')->orderBy('id', 'desc');
        }

        return $query->selectRaw("*, 0 AS distance")->orderBy('id', 'desc');
    }


}
