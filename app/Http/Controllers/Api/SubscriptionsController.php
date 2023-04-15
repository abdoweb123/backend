<?php

namespace App\Http\Controllers\Api;

use FCM;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Models\Trip;
use App\Models\CarSetting;
use App\Models\SpecialCar;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\TripServeices;
use App\Models\RequerdEquipment;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;


class SubscriptionsController extends Controller
{

    public function NewSubscription(Request $request)
    {

        $NewItem= new Subscription;
        $NewItem->user_id = Auth::user()->id;
        $NewItem->driver_id = $request->driver_id;
        $NewItem->from_date =  $request->from_date;
        $NewItem->to_date =  $request->to_date;
        $NewItem->going_coming =  $request->going_coming;
        $NewItem->save();

        return response()->json(['message' => 'Subscription unsaved successfully']);
    }

    public function SpecialCarTypes(){
        $SubscriptionsTypes=SpecialCar::where('type', 3)->get();
        return response()->json(['SubscriptionsTypes' => $SubscriptionsTypes]);
    }

    public function MySubscriptions()
    {
        $MySubscriptions=Subscription::where('user_id',Auth::user()->id)->first();

        return response()->json(['MySubscriptions' => $MySubscriptions]);

    }

    function haversineGreatCircleDistance(Request $request){
        $latitudeFrom=$request->latitudeFrom;
        $longitudeFrom=$request->longitudeFrom;
        $latitudeTo=$request->latitudeTo;
        $longitudeTo=$request->longitudeTo;
        $earthRadius = 6371000;

        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
          cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $final= $angle * $earthRadius;
        $est=$final*1.2;

        $PaymentMethod = $request->PriceMethod ;
        $car_type_id=$request->car_type_id;
        $equipment_id=$request->equipment_id;
        $days=$request->days;
        $going_coming=$request->going_coming;
        if ($going_coming == 0) {
            $GoingComing = 1;
        } else {
            $GoingComing = 2;
        }


        $kilo=$final/1000;



        $RequerdEquipment=RequerdEquipment::where('id',$equipment_id)->pluck('price')->first();
        $car_type_id=SpecialCar::where('id',$car_type_id)->pluck('price')->first();

        if ($PaymentMethod == 'byrequest') {
        $costpramter= CarSetting::where('id',2)->pluck('byrequest')->first();
        $finalcost=($costpramter+$RequerdEquipment+$car_type_id)*$days*$GoingComing;
            return response()->json(['distance' => $final,
            'Cost' => $finalcost/1000,
            'est' => $est
            ]);

        } elseif ($PaymentMethod == 'hourly'){
            $costpramter= CarSetting::where('id',2)->pluck('byhour')->first();
            $fa=($costpramter*$est+$RequerdEquipment+$car_type_id)*$days*$GoingComing;
            return response()->json(['distance' => $final,
            'Cost' => $fa/1000,
            'est' => $est
            ]);
        }
        else {
            $costpramter= CarSetting::where('id',2)->pluck('price')->first();
            $fa=($costpramter*$kilo+$RequerdEquipment+$car_type_id)*$days*$GoingComing;
            return response()->json(['distance' => $final,
            'Cost' => $fa/1000,
            'est' => $est
            ]);

        }

    }

    public function ConfirmRide(Request $request){
        $lng = $request->lng;
        $lat = $request->lat;

        $Requesteduser=\Request::user();

        if(empty($lng) || empty($lng)){
        return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
        // $Drivers = User::withDistance($lat, $lng)->with('MyCar')
        //     ->where('id', '!=', Auth::user()->id)
        //     ->where(function ($q) {
        //         $q->whereNotNull('lat')->orWhereNotNull('lng');
        // });

        $Drivers=User::whereHas('DriverSpacliy', function($q){
        $q->where('drivers_specialty_id', '=', '2');
        })->get();

        if ($Drivers->count() <= 0) {
        return response()->json(['code' => 105, 'message' => 'no near by found']);
        }

        $trip= new Trip;
        $trip->client_id = Auth::user()->id;
        $trip->from_lat = $request->from_lat;
        $trip->from_lng = $request->from_lng;
        $trip->to_lat = $request->to_lat;
        $trip->to_lng = $request->to_lng;
        $trip->address_from = $request->address_from;
        $trip->address_to = $request->address_to;
        $trip->payment_method = $request->price_method;
        $trip->ride_type = 17;
        if ($trip->save()) {
            $NewItem= new Subscription;
            $NewItem->trip_id = $trip->id;
            $NewItem->user_id = Auth::user()->id;
            $NewItem->driver_id = Auth::user()->id;
            $NewItem->to_address = $request->address_to;
            $NewItem->from_address = $request->address_from;
            $NewItem->to_time = $request->to_time;
            $NewItem->from_date = $request->from_date;
            $NewItem->from_time = $request->from_time;

            $NewItem->to_date = $request->to_date;
            $NewItem->going_coming = $request->going_coming;
            $NewItem->working_days = $request->working_days;
            $NewItem->required_equipment = $request->required_equipment;
            $NewItem->driver_spacliy_id = $request->driver_spacliy_id;
            $NewItem->save();
        }


        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('The Best');
        $notificationBuilder->setBody('You have trip request')
                        ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['lat' => $lat,
                            'lng' => $lng,
                            'user_id' => $Requesteduser->id,
                            'name' => $Requesteduser->name,
                            'phone' => $Requesteduser->phone,
                            'trip_id' => $trip->id,

        ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // $token = User::where('id',$id)->pluck('fcm_token')->first();
        $tokens = $Drivers->pluck('fcm_token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return response()->json(['massage' => 'Please wait for driver approval']);
    }

    public function CancelRide(){
        $user=Auth::user();
        $UpdateItem= User::find($user->id);
        $UpdateItem->status = 'available';
        $UpdateItem->save();

        $CancelRide= Trip::where('driver_id',$user->id)->orderBy('created_at', 'desc')->first();
        $CancelRide->status = 'Canceled';
        $CancelRide->save();

        return response()->json(['message' => 'Ride Had Been Cancel']);
    }

    public function EndRide(){
        $user=Auth::user();
        $UpdateItem= User::find($user->id);
        $UpdateItem->status = 'available';
        $UpdateItem->save();

        $CancelRide= Trip::where('driver_id',$user->id)->orderBy('created_at', 'desc')->first();
        $CancelRide->status = 'Completed';
        $CancelRide->save();

        return response()->json(['message' => 'Ride Had Been Completed']);

    }

    public function RidePrice(Request $request){

        $user=Auth::user();
        $RidePrice= Trip::where('driver_id',$user->id)->orderBy('created_at', 'desc')->first();
        $RidePrice->total = $request->total;
        $RidePrice->payment_method = $request->payment_method;
        $RidePrice->save();

        return response()->json(['message' => 'Done']);

    }

    public function RequerdEquipment(){
        $RequerdEquipment=RequerdEquipment::all();
        return response()->json([
        'RequerdEquipment' => $RequerdEquipment,
        ]);

    }


}
