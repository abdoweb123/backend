<?php

namespace App\Http\Resources;

use App\User;
use App\Worklap;
use App\Medication;
use Illuminate\Http\Resources\Json\JsonResource;

class FristStepGetTaxi extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $grades = array();
        foreach ($this->resource as $grade) {
           $grades[] = array(
               'id' => $grade->id,
               'Driver name' => User::where('id',$grade->id)->pluck('name')->first(),
               'Driver image' => $grade->has_image,
               'lat' => $grade->lat,
               'lng' => $grade->lng,
               'distance' => $grade->distance,
               'cost' => floor($grade->distance * 20),
           );
        }
        return $grades;
    }
}
