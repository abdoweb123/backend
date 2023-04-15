<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;


class RoadServiceOption extends Model
{

    use CrudTrait;

    protected $table='road_services_options';

    protected $fillable=['name','road_services_id','price','image','description_en','description'];
    protected $appends = ['has_image'];


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

    public function getHasImageAttribute()
    {

        $value = $this->getAttributes()['image'];

        if ($value) {
            return asset('storage/restaurants/' . $value);
        } else {
            return asset('images/profile/no-image.png');
        }

    }


    public function RoadService()
    {
        return $this->belongsTo(RoadService::class, 'road_services_id');
    }




}
