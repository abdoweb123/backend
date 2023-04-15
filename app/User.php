<?php

namespace App;

use App\Models\Trip;
use App\Models\Country;
use App\Models\CarCompany;
use Illuminate\Support\Str;
use App\Models\CarsRegistration;
use App\Models\DriversSpecialty;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens,CrudTrait;

    const  IMAGE_FOLDER='public/users';

    public $preventAttrSet = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','image'
        ,'is_admin','is_driver','lat','lng','status','nationality','birth_date',
        'imgcert',' ','address','expierd_date','country_id','ssidfront','ssidback',
        'passport','phone_intreal','car_company_id','start_blocked_at','end_blocked_at'
    ];

    // protected $appends = ['has_image'];


    // public function getHasImageAttribute()
    // {
    //     $value = $this->getAttributes()['image'];

    //     if ($value) {
    //         return asset('storage/users/' . $value);
    //     } else {
    //         return asset('images/profile/no-image.png');
    //     }

    // }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_driver' => 'array',
    ];


    public function scopeTaxi($query)
    {
        return $query->whereHas('DriverSpacliy', function ($query) {
            $query->where('drivers_specialty_id', '=', '2');
        })->get();
    }

    // public function getImageAttribute($value)
    // {
    //     if ($value) {
    //         return asset('storage/users/' . $value);
    //     } else {
    //         return asset('images/profile/no-image.png');
    //     }
    // }

    public function setImageAttribute($value)
    {

        if ($this->preventAttrSet) {
            $this->attributes['image'] = $value;
        } else {
            $attribute_name = "image";
            // or use your own disk, defined in config/filesystems.php
            $disk = "public";
            // destination path relative to the disk above
            $destination_path = "public/users";


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

    public function setImgcertAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['imgcert'] = $value;
        } else {
            $attribute_name = "imgcert";
            // or use your own disk, defined in config/filesystems.php
            $disk = "public";
            // destination path relative to the disk above
            $destination_path = "public/users";


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

    public function setPassportAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['passport'] = $value;
        } else {
            $attribute_name = "passport";
            // or use your own disk, defined in config/filesystems.php
            $disk = "public";
            // destination path relative to the disk above
            $destination_path = "public/users";


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
    public function setSsidfrontAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['ssidfront'] = $value;
        } else {
            $attribute_name = "ssidfront";
            // or use your own disk, defined in config/filesystems.php
            $disk = "public";
            // destination path relative to the disk above
            $destination_path = "public/users";


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

    public function setSsidbackAttribute($value)
    {
        if ($this->preventAttrSet) {
            $this->attributes['ssidback'] = $value;
        } else {
            $attribute_name = "ssidback";
            // or use your own disk, defined in config/filesystems.php
            $disk = "public";
            // destination path relative to the disk above
            $destination_path = "public/users";


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

    public function MyCar(){
        return $this->hasMany(CarsRegistration::class,'user_id');

    }

    public function Country(){
        return $this->belongsTo(Country::class,'country_id');
    }


    public function CompanyOwner(){
        return $this->belongsTo(CarCompany::class,'car_company_id');
    }

    public function DriverSpacliy(){
        return $this->belongsToMany(DriversSpecialty::class);
    }

    public function Trip(){
        return $this->hasMany(Trip::class,'driver_id');
    }

    public function MyCarApi(){
        return $this->belongsTo(CarsRegistration::class,'user_id');

    }

    public function MyTrip(){
        return $this->hasMany(Trip::class,'driver_id');
    }

    public function ClientTrip(){
        return $this->hasMany(Trip::class,'client_id');
    }

    public function getClientTripCount()
    {
         return $this->ClientTrip()->count();
    }

    public function getMyTripCount()
    {
         return $this->MyTrip()->count();
    }

    public function TotalTrip(){
        return $this->hasMany(Trip::class,'client_id');
    }
    public function getTotalTripSum()
    {
         return $this->MyTrip()->sum('total');
    }

    public function getDriverTotal()
    {
         return $this->MyTrip()->sum('driver_cut');
    }
}
