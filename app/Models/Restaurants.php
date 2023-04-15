<?php

namespace App\Models;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Restaurants extends Model
{
    use CrudTrait;

    protected $table = 'restaurants_cafes';
    protected $fillable = ['name','image',
    'category_id','delivery_price','type_id',
    'address','description','country','government',
    'district','lat','lng',
    'ownerimage','place_owner_name','place_email',
    'place_phone',
    'description_en','name_en','address_en','imgcert','signatureimage','banking_id',
    'order_limit','branches','working_hours',
    'time_frame','responsibles','show','otherimage','bank_info','parent_user',
    ];
    public $preventAttrSet = false;


    protected $appends = ['has_image'];

        protected $casts = [
        'category_id' => 'array',

            ];



    public function RestaurantCategory()
    {
         return $this->belongsToMany(RestaurantCategory::class,'rest_cat');
    }

    public function MainCategories()
    {
         return $this->belongsTo(MainCategories::class,'type_id');
    }

    public function getDateFormat()
    {
     return 'Y-m-d H:i:s.u';
    }

    public function BankingPlace(){
        return $this->belongsTo(BankingPlace::class,'banking_id');
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

    public function RestaurantMenu(){
        return $this->hasMany(RestaurantMenu::class,'restaurant_id');
    }

    public function MenuesCategories(){
        return $this->hasMany(MenuCategory::class,'restaurant_id');
    }

    public function scopeWithDistance($query, $lat, $lng)
    {
        $lat = floatval($lat);
        $lng = floatval($lng);
        if (!empty($lat) || !empty($lng)) {
            return $query->selectRaw(
                "*,round(( '6371000' * acos( cos( radians('$lat') ) * cos( radians( lat ) )
                 * cos( radians( lng ) - radians('$lng') ) + sin( radians('$lat') )
                  * sin( radians( lat ) ) ) ),2) AS distance "
            )->orderBy('distance', 'asc')->orderBy('id', 'desc');
        }

        return $query->selectRaw("*, 0 AS distance")->orderBy('id', 'desc');
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

    public function setimgcertAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['imgcert'] = $value;
        } else {
            $attribute_name = "imgcert";
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

    public function setotherimageAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['otherimage'] = $value;
        } else {
            $attribute_name = "otherimage";
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


    public function setsignatureimageAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['signatureimage'] = $value;
        } else {
            $attribute_name = "signatureimage";
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

    public function setownerimageAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['ownerimage'] = $value;
        } else {
            $attribute_name = "ownerimage";
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

    public function City(){
        return $this->belongsTo(Cities::class,'government');
    }

    public function Districts(){
        return $this->belongsTo(Districts::class,'district');
    }


    // public function RestaurantCategory(){
    //     return $this->belongsTo(RestaurantCategory::class,'category_id');
    // }


    public function Owner()
    {
        return $this->belongsTo('App\User', 'parent_user');
    }

    public function OrderItem()
    {
        return $this->belongsTo(OrderItem::class, 'place_id');
    }
    public function getOrderItemCount()
    {
         return $this->OrderItem()->count();
    }
}
