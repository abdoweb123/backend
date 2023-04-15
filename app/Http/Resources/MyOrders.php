<?php

namespace App\Http\Resources;

use App\Models\OrderItem;
use App\Models\Restaurants;
use App\Models\Itemattribute;
use App\Models\RestaurantMenu;
use Illuminate\Http\Resources\Json\JsonResource;

class MyOrders extends JsonResource
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
            $Orderlist = $grade->OrderItems;

            foreach($Orderlist as $OrderItems) {

            $OrderItemsName = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('name')->first();
            $OrderItemsPlace = Restaurants::where('id', $OrderItems->place_id)->pluck('name')->first();
            $OrderItemattribute = Itemattribute::where('id', $OrderItems->variation_id)->pluck('name')->first();
            $OrderAddress = Restaurants::where('id', $OrderItems->place_id)->pluck('address')->first();
            $Orderimage = RestaurantMenu::where('id', $OrderItems->item_id)->first();
            $test= $Orderimage->has_image;

            $FirstAttrItemsNameAr = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_title')->first();
            $FirstAttrItemsNameEn = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_title_en')->first();
            $FirstAttrItemsBody = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_body')->first();




            $SecondAttrItemsNameAr = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_title_two')->first();
            $SecondAttrItemsNameEn = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_title_en_two')->first();
            $SecondAttrItemsBody = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_body_two')->first();


            $ThirdAttrItemsNameAr = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_title_three')->first();
            $ThirdAttrItemsNameEn = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_title_en_three')->first();
            $ThirdAttrItemsBody = RestaurantMenu::where('id', $OrderItems->item_id)->pluck('attribute_body_three')->first();

            if ($OrderItems->attribute_body == null) {
                $OrderItems->setAttribute('Item Name', $OrderItemsName);
                $OrderItems->setAttribute('Restaurant Name', $OrderItemsPlace);
                $OrderItems->setAttribute('Restaurant address', $OrderAddress);
                $OrderItems->setAttribute('Restaurant image', $test);

                $OrderItems->setAttribute('FirstAttrItemsNameAr', $FirstAttrItemsNameAr);
                $OrderItems->setAttribute('FirstAttrItemsNameEn', $FirstAttrItemsNameEn);
                $OrderItems->setAttribute('FirstAttrItemsBody', null);



              }

              elseif ($OrderItems->attribute_body_two == null) {
                $OrderItems->setAttribute('Item Name', $OrderItemsName);
                $OrderItems->setAttribute('Restaurant Name', $OrderItemsPlace);
                $OrderItems->setAttribute('Restaurant address', $OrderAddress);
                $OrderItems->setAttribute('Restaurant image', $test);
                $a7a=json_decode($FirstAttrItemsBody);
                $userFas=$a7a[$OrderItems->attribute_body];

                $OrderItems->setAttribute('FirstAttrItemsNameAr', $FirstAttrItemsNameAr);
                $OrderItems->setAttribute('FirstAttrItemsNameEn', $FirstAttrItemsNameEn);
                $OrderItems->setAttribute('FirstAttrItemsBody', $userFas);

                $OrderItems->setAttribute('SecondAttrItemsNameAr', $SecondAttrItemsNameAr);
                $OrderItems->setAttribute('SecondAttrItemsNameEn', $SecondAttrItemsNameEn);
                $OrderItems->setAttribute('SecondAttrItemsBody', null);
              }


              elseif ($OrderItems->attribute_body_three == null ) {
                $OrderItems->setAttribute('Item Name', $OrderItemsName);
                $OrderItems->setAttribute('Restaurant Name', $OrderItemsPlace);
                $OrderItems->setAttribute('Restaurant address', $OrderAddress);
                $OrderItems->setAttribute('Restaurant image', $test);
                $a7a=json_decode($FirstAttrItemsBody);
                $userFas=$a7a[$OrderItems->attribute_body];

                $a7as=json_decode($SecondAttrItemsBody);
                $userFass=$a7as[$OrderItems->attribute_body_two];
                $OrderItems->setAttribute('FirstAttrItemsNameAr', $FirstAttrItemsNameAr);
                $OrderItems->setAttribute('FirstAttrItemsNameEn', $FirstAttrItemsNameEn);
                $OrderItems->setAttribute('FirstAttrItemsBody', $userFas);

                $OrderItems->setAttribute('SecondAttrItemsNameAr', $SecondAttrItemsNameAr);
                $OrderItems->setAttribute('SecondAttrItemsNameEn', $SecondAttrItemsNameEn);
                $OrderItems->setAttribute('SecondAttrItemsBody', $userFass);

                $OrderItems->setAttribute('ThirdAttrItemsNameAr', $ThirdAttrItemsNameAr);
                $OrderItems->setAttribute('ThirdAttrItemsNameEn', $ThirdAttrItemsNameEn);
                $OrderItems->setAttribute('ThirdAttrItemsBody',null);
              }
              else {
                $a7a=json_decode($FirstAttrItemsBody);
                $userFas=$a7a[$OrderItems->attribute_body];
                $a7asss=json_decode($ThirdAttrItemsBody);
                $userFasss=$a7asss[$OrderItems->attribute_body_three];
                $a7as=json_decode($SecondAttrItemsBody);
                $userFass=$a7as[$OrderItems->attribute_body_two];

                $OrderItems->setAttribute('Item Name', $OrderItemsName);
                $OrderItems->setAttribute('Restaurant Name', $OrderItemsPlace);
                $OrderItems->setAttribute('Restaurant address', $OrderAddress);
                $OrderItems->setAttribute('Restaurant image', $test);


                $OrderItems->setAttribute('FirstAttrItemsNameAr', $FirstAttrItemsNameAr);
                $OrderItems->setAttribute('FirstAttrItemsNameEn', $FirstAttrItemsNameEn);
                $OrderItems->setAttribute('FirstAttrItemsBody', $userFas);

                $OrderItems->setAttribute('SecondAttrItemsNameAr', $SecondAttrItemsNameAr);
                $OrderItems->setAttribute('SecondAttrItemsNameEn', $SecondAttrItemsNameEn);
                $OrderItems->setAttribute('SecondAttrItemsBody', $userFass);

                $OrderItems->setAttribute('ThirdAttrItemsNameAr', $ThirdAttrItemsNameAr);
                $OrderItems->setAttribute('ThirdAttrItemsNameEn', $ThirdAttrItemsNameEn);
                $OrderItems->setAttribute('ThirdAttrItemsBody', $userFasss);
              }









            // $OrderItems->setAttribute('Item Name', $OrderItemsName);
            // $OrderItems->setAttribute('Restaurant Name', $OrderItemsPlace);
            // $OrderItems->setAttribute('Restaurant address', $OrderAddress);
            // $OrderItems->setAttribute('Restaurant image', $test);


            // $OrderItems->setAttribute('FirstAttrItemsNameAr', $FirstAttrItemsNameAr);
            // $OrderItems->setAttribute('FirstAttrItemsNameEn', $FirstAttrItemsNameEn);
            // $OrderItems->setAttribute('FirstAttrItemsBody', $userFas);

            // $OrderItems->setAttribute('SecondAttrItemsNameAr', $SecondAttrItemsNameAr);
            // $OrderItems->setAttribute('SecondAttrItemsNameEn', $SecondAttrItemsNameEn);
            // $OrderItems->setAttribute('SecondAttrItemsBody', $userFass);

            // $OrderItems->setAttribute('ThirdAttrItemsNameAr', $ThirdAttrItemsNameAr);
            // $OrderItems->setAttribute('ThirdAttrItemsNameEn', $ThirdAttrItemsNameEn);
            // $OrderItems->setAttribute('ThirdAttrItemsBody', $userFasss);








           }
           $grades[] = array(
               'id' => $grade->id,
               'username' => $grade->username,
               'user_id' => $grade->user_id,
               'user_id' => $grade->user_id,
               'lat' => $grade->lat,
               'lng' => $grade->lng,
               'comment' => $grade->comment,
               'address' => $grade->address,
               'phone' => $grade->phone,
               'total' => $grade->total,
               'trip' => $grade->trip,
               'order_items'=> $Orderlist,
               'status'=> $grade->status,
               'order date'=>$grade->created_at,
           );
        }
        return $grades;
    }
}
