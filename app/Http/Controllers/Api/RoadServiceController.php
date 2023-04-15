<?php

namespace App\Http\Controllers\Api;

use FCM;
use Auth;
use App\User;
use App\Models\Trip;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CarSetting;
use App\Models\RoadService;
use Illuminate\Http\Request;
use App\Models\TripServeices;
use App\Models\RestaurantMenu;
use App\Http\Resources\MyOrders;
use App\Models\RoadServiceOption;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use App\Http\Resources\FristStepGetTaxi;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class RoadServiceController extends Controller
{

    public function RoadServCategories(){
        $RoadService=RoadService::all();
        return response()->json(['RoadService' => $RoadService]);
    }



    public function RoadServOptions($id){
        $RoadServOptions=RoadServiceOption::where('road_services_id',$id)->get();
        return response()->json(['RoadServOptions' => $RoadServOptions]);
    }

    public function StartRide(Request $request)
    {
        $user=Auth::user();
        $UpdateItem= User::find($user->id);
        $UpdateItem->status = 'unavailable';
        $UpdateItem->save();

        $NewItem= new Trip;
        $NewItem->client_id = $request->client_id;
        $NewItem->driver_id = $user->id;
        $NewItem->from_lat = $request->from_lat;
        $NewItem->from_lng = $request->from_lng;
        $NewItem->to_lat = $request->to_lat;
        $NewItem->to_lng = $request->to_lng;
        $NewItem->save();
        return response()->json(['code' => 105, 'message' => 'done']);


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
        $service_count=$request->service_count;
        $service_id=$request->service_id;
        $kilo=$final/1000;


        $RoadServiceOption=RoadServiceOption::where('id',$service_id)->pluck('price')->first();

        if ($PaymentMethod == 'byrequest') {
        $costpramter= CarSetting::where('id',4)->pluck('byrequest')->first();
        $finalcost=($kilo*$costpramter)+($RoadServiceOption*$service_count);
            return response()->json(['distance' => $final,
            'Cost' => $finalcost,
            'est' => $est
            ]);

        } elseif ($PaymentMethod == 'hourly'){
            $costpramter= CarSetting::where('id',4)->pluck('byhour')->first();
            $fa=$costpramter*$est+$RoadServiceOption*$service_count;
            return response()->json(['distance' => $final,
            'Cost' => $fa,
            'est' => $est
            ]);
        }
        else {
            $costpramter= CarSetting::where('id',4)->pluck('price')->first();
            $finalcost=($kilo*$costpramter)+($RoadServiceOption*$service_count);
            return response()->json(['distance' => $final,
            'Cost' => $finalcost,
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
        $trip->total = $request->total;
        $trip->ride_type = 15;


        if ($trip->save()) {
            $NewItem= new TripServeices;
            $NewItem->trip_id = $trip->id;
            $NewItem->service_name = $request->service_name;
            $NewItem->service_count = $request->service_count;
            $NewItem->note = $request->note;
            $NewItem->service_image = $request->service_image;
            $NewItem->service_desc = $request->service_desc;
            $NewItem->service_price = $request->service_price;
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

    public function NearestCars(Request $request)
    {
        $lng = $request->lng;
        $lat = $request->lat;
        $car_model=$request->car_model;


        if(empty($lng) || empty($lng)){
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
        $Drivers = User::withDistance($lat, $lng)
            ->where('id', '!=', Auth::user()->id)
            ->where(function ($q) {
                $q->whereNotNull('lat')->orWhereNotNull('lng');
            });

        $Drivers->where('is_driver',1)
       ->get();

        $Drivers = $Drivers->paginate(5);
        if ($Drivers->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }

        return new FristStepGetTaxi($Drivers);

    }


}
