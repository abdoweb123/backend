<?php

namespace App\Models;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class RestaurantMenu extends Model
{
    use CrudTrait;

    protected $table = 'restaurantmenu';
    protected $fillable = ['name','name_en','price','cat_id','image',
    'description','description_en','restaurant_id',
    'menu_category_id',
    'attribute_title','attribute_body','additional_item_ar','additional_item_en','attribute_title_en',
    'attribute_title_en_two','attribute_title_en_three','attribute_body_two','attribute_body_three',
    'attribute_title_two','attribute_title_three'
    ];

    protected $appends = ['has_image'];

    protected $casts = [
        'additional_item_ar' => 'array',
        ];

    public $preventAttrSet = false;

    // public function getAttributeBodyAttribute()

    // {
    //     $image = $this->getAttributes()['attribute_body'];

    //     $te=json_decode($image);

    //     return  $ce;

    //     // if (!empty($image)) {
    //     //      $ce=str_replace('\', "", $image);
    //     //      return  $ce;
    //     //     }
    //     // else {
    //     //     return false;
    //     // }
    // }



    public function MainCategories()
    {
         return $this->belongsTo(MainCategories::class,'cat_id');
    }

    public function getHasImageAttribute()
    {
        $value = $this->getAttributes()['image'];

        if ($value) {
            return asset('storage/public/' . $value);
        } else {
            return asset('images/profile/no-image.png');
        }
    }


    public function Restaurants()
    {
        return $this->belongsTo(Restaurants::class,'restaurant_id');
    }
    public function menucategory()
    {
        return $this->belongsTo(MenuCategory::class,'menu_category_id');
    }

    // public function Itemattributes()
    // {
    //     return $this->hasMany(Itemattribute::class,'item_id');
    // }


    // public function AdditionalItem()
    // {
    //      return $this->belongsTo(AdditionalItem::class,'additional_item_ar');
    // }
    public function AdditionalItems()
    {
        return $this->belongsToMany(AdditionalItem::class, 'restaurantmenu_additional');
    }

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

    public function setImageAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['image'] = $value;
        } else {
            $attribute_name = "image";
            // or use your own disk, defined in config/filesystems.php
            $disk = "public";
            // destination path relative to the disk above
            $destination_path = "public/restaurants";


            // if a base64 was sent, store it in the db
            if (Str::startsWith($value, 'data:image'))
            {
                // 0. Make the image
                $image = \Image::make($value)->encode('jpg', 90);

                // 1. Generate a filename.
                $filename = md5($value.time()).'.jpg';

                // 2. Store the image on disk.
                \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

                // 3. Delete the previous image, if there was one.
                \Storage::disk($disk)->delete($this->{$attribute_name});

                // 4. Save the public path to the database
                // but first, remove "public/" from the path, since we're pointing to it
                // from the root folder; that way, what gets saved in the db
                // is the public URL (everything that comes after the domain name)
                $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
                $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
            }
        }
    }



}
