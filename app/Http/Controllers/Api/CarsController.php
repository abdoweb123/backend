<?php

namespace App\Http\Controllers\Api;

use FCM;
use Auth;
use App\User;
use App\Models\Trip;
use App\Models\CarSetting;
use App\Models\SpecialCar;
use App\Models\CarFactories;
use Illuminate\Http\Request;
use App\Models\TripServeices;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use App\Http\Resources\FristStepGetTaxi;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class CarsController extends Controller
{

    public function GetCars(){
        $Cars=CarFactories::paginate();
        return response()->json(['Cars' => $Cars]);
    }

    public function SpecialCarTypes(){
        $SpecialCar=SpecialCar::all();
        return response()->json(['SpecialCar' => $SpecialCar]);
    }

    public function GetSpecialCar(Request $request)
    {
        $lng = $request->lng;
        $lat = $request->lat;
        $category=$request->category_id;



        if(empty($lng) || empty($lng)){
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
        $Drivers = User::withDistance($lat, $lng)
            ->where('id', '!=', Auth::user()->id)
            ->where(function ($q) {
                $q->whereNotNull('lat')->orWhereNotNull('lng');
            });

        $Drivers->where('is_driver',1)
        ->where('status','available')
        ->whereHas('MyCar', function($q) use ($category)
        {
            $q->where('special_cars_id','=',$category);

        })->get();

        $Drivers = $Drivers->paginate(5);
        if ($Drivers->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }

        return new FristStepGetTaxi($Drivers);


        // return response()->json([
        //     'code' => 100,
        //     'message' => trans('api.get_near_by'),
        //     'distans' => $Drivers
        // ]);


    }

    public function GetFurnitureCar(Request $request)
    {
        $lng = $request->lng;
        $lat = $request->lat;


        if(empty($lng) || empty($lng)){
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
        $Drivers = User::withDistance($lat, $lng)
            ->where('id', '!=', Auth::user()->id)
            ->where(function ($q) {
                $q->whereNotNull('lat')->orWhereNotNull('lng');
            });

        $Drivers->where('is_driver',1)
        ->where('status','available')
        ->whereHas('MyCar', function($q)
        {
            $q->where('type','truck');

        })->get();

        $Drivers = $Drivers->paginate(5);
        if ($Drivers->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }

        return new FristStepGetTaxi($Drivers);

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
        $NewItem->address_from = $request->address_from;
        $NewItem->address_to = $request->address_to;
        $NewItem->ride_type	 = 2;
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
        $kilo=$final/1000;
        $est=$final*1.2;
        $costpramter= CarSetting::where('id',2)->pluck('price')->first();


        return response()->json(['distance' => $final,
        'Cost' => $kilo*$costpramter,
        'costpramter' => $costpramter,

        'est' => $est
        ]);

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
        $trip->ride_type = 21;


        if ($trip->save()) {
            $NewItem= new TripServeices;
            $NewItem->trip_id = $trip->id;
            $NewItem->required_equipment = $request->required_equipment;
            $NewItem->price_method = $request->price_method;
            $NewItem->car_type = $request->car_type;
            $NewItem->price_method_from = $request->price_method_from;
            $NewItem->price_method_to = $request->price_method_to;
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
        ->where('status','available')
        ->whereHas('MyCar', function($q) use($car_model)
        {
            $q->where('car_model',$car_model);

        })->get();

        $Drivers = $Drivers->paginate(5);
        if ($Drivers->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }

        return new FristStepGetTaxi($Drivers);

    }








}
