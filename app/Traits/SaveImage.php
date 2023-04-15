<?php

namespace App\Traits;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;

Trait  SaveImage
{
     function saveImage($photo,$folder){
        $disk = "public";
        // 0. Make the image
        $image = \Image::make($photo)->encode('jpg', 90);

        // 1. Generate a filename.
        $filename = md5($photo.time()).'.jpg';

        // 2. Store the image on disk.
        \Storage::disk($disk)->put($folder.'/'.$filename, $image->stream());

        $public_destination_path = Str::replaceFirst('public/', '', $folder);
        return $file_name = $public_destination_path.'/'.$filename;
    }


}