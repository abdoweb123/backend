<?php

    namespace App\Http\Controllers\Api;

    use FCM;
    use Auth;
    use App\User;
    use Carbon\Carbon;
    use App\Models\Trip;
    use App\Models\Order;
    use App\Models\CarSetting;
    use App\Models\SpecialCar;
    use App\Models\Restaurants;
    use App\Models\ResturantCut;
    use Illuminate\Http\Request;
    use App\Models\ServcrSetting;
    use App\Http\Controllers\Controller;
    use LaravelFCM\Message\OptionsBuilder;
    use App\Http\Resources\FristStepGetTaxi;
    use LaravelFCM\Message\PayloadDataBuilder;
    use LaravelFCM\Message\PayloadNotificationBuilder;

        class TripController extends Controller
        {

        public function MyTripsForClient($id){
        $Trips=Trip::where('client_id',Auth::user()->id)
            ->where('ride_type',$id)->get();
        if($Trips->count()<=0){
        return response()->json(['code' => 105, 'message' => 'You do not have current trips for this type']);
        }
        return response()->json(['Trips' => $Trips]);
        }

        public function ChangeOrderTrip(Request $request,$id){
            $Update = Trip::find($id);
            $Update->status = $request->status;
            $Update->save();
            return response()->json(['status' => '200',
            'massage' => 'You Change The Trip Successfully']);

        }

        public function MyTripsForDriver(){
        $Trips=Trip::where('driver_id',Auth::user()->id)->get();
        if($Trips->count()<=0){
        return response()->json(['code' => 105, 'message' => 'not found']);
        }
        return response()->json(['Trips' => $Trips]);
        }

        public function AddTrip(){
        $user=Auth::user();
        $Trip= new Trip;
        $Trip->client_id = $user->id;
        $Trip->from_lat = $request->from_lat;
        $Trip->from_lng = $request->from_lng;
        $Trip->to_lat	 = $request->to_lat;
        $Trip->to_lng = $request->to_lng;
        $Trip->from_lat = $request->from_lat;
        $Trip->total = $request->total;
        $Trip->save();
        return response()->json(['message' => 'Done']);

        }

        public function GetTaxi(Request $request)
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

        $Drivers->where('is_driver',1)->where('status','available');
        $Drivers = $Drivers->paginate(5);
        if ($Drivers->count() <= 0) {
        return response()->json(['code' => 105, 'message' => 'no near by found']);
        }

        $rest=$Drivers->pluck('id');

        return new FristStepGetTaxi($Drivers);

        }
        public function StartRide(Request $request,$id)
        {
        $user=Auth::user();
        $UpdateItem= User::find($user->id);
        $UpdateItem->status = 'unavailable';
        $UpdateItem->save();

        $UpdateItem= Trip::find($id);
        $UpdateItem->status = 'InProgress';
        $UpdateItem->save();

        $trip=Trip::where('id',$id)->first();
        $userTrip=$trip->client_id;
        $ClientToken=User::where('id',$userTrip)->pluck('fcm_token')->first();

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('The Best');
        $notificationBuilder->setBody('You Trip Started')
                        ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
        'ride' => 'start',
        'trip_id' => $id,

        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $tokens = $ClientToken;
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
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
        $byKelo=$final/1000;

        $est=$final*1.5;

        $costpramter= CarSetting::where('id',1)->pluck('byrequest')->first();


        return response()->json(['distance' => $final,
        'Cost' => ($byKelo*$costpramter),
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

        $NewItem= new Trip;
        $NewItem->client_id = Auth::user()->id;
        $NewItem->from_lat = $request->from_lat;
        $NewItem->from_lng = $request->from_lng;
        $NewItem->to_lat = $request->to_lat;
        $NewItem->to_lng = $request->to_lng;
        $NewItem->address_from = $request->address_from;
        $NewItem->address_to = $request->address_to;
        $NewItem->ride_type	 = 1;
        $NewItem->total = $request->total;
        $NewItem->save();


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
                            'trip_id' => $NewItem->id,

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

        public function EndRide($id){
            // $user=Auth::user();
            $user=User::where('id',144)->first();

            $UpdateItem= User::find($user->id);
            $UpdateItem->status = 'available';
            $UpdateItem->save();

            $UpdateItem= Trip::find($id);
            $UpdateItem->status = 'Completed';
            $UpdateItem->save();

            $trip=Trip::where('id',$id)->first();
            $userTrip=$trip->client_id;
            $ClientToken=User::where('id',$userTrip)->pluck('fcm_token')->first();

            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);

            $notificationBuilder = new PayloadNotificationBuilder('The Best');
            $notificationBuilder->setBody('Your Trip is over!')
                            ->setSound('default');

            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData([
                'ride' => 'end',
                'trip_id' => $id,

            ]);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            $tokens = $ClientToken;
            $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
            return response()->json(['code' => 105, 'message' => 'done']);



        return response()->json(['message' => 'Ride Had Been Completed']);

        }

        public function RidePrice(Request $request){
            $user=Auth::user();

            $order_idFromTrip= Trip::where('id',$request->trip_id)->pluck('order_id')->first();
            $restaurant_idFromTrip=Order::where('id',$order_idFromTrip)->pluck('restaurant_id')->first();
            $total=$request->total;
            $total_order=$request->total_order;



            
            if ($total_order != null) {
                $ResturantCut=ResturantCut::where('rest_id',$restaurant_idFromTrip)->first();

                if ($ResturantCut->persentage != null) {
                    $com=$total_order/$ResturantCut->persentage;
                    $newtotal=$total_order-$com;
                    $OrderPrice=Order::where('id',$order_idFromTrip)->first();
                    $OrderPrice->app_cut = $newtotal;
                    $OrderPrice->rest_cut = $com;
                    $OrderPrice->save();

                }else {
                    $OrderPrice=Order::where('id',$order_idFromTrip)->first();
                    $OrderPrice->rest_cut = $total_order;
                    $OrderPrice->save();
                }}
            if ($total != null) {
                $userc=User::where('id',144)->first();
                $driverPrice=ServcrSetting::where('user_id',$userc->id)->first();
                if ($driverPrice->byrequest != null) {
                    
                    $RidePrice= Trip::where('id',$request->trip_id)->first();
                    $requ=$driverPrice->byrequest;
                    $drivercuts=$total-$requ;
                    $RidePrice->app_cut = $drivercuts;
                    $RidePrice->driver_cut = $driverPrice->byrequest;
                    $RidePrice->save();
                    return response()->json(['total' => $total,
                    'app_cut' => $drivercuts,
                    'driver_cut' => $driverPrice->byrequest,
                    ]);


                }
                elseif($driverPrice->salary != null) {
                    $RidePrice= Trip::where('id',$request->trip_id)->first();
                    $RidePrice->app_cut = $request->total;
                    $RidePrice->save();
                }
                elseif($driverPrice->commission != null) {
                    $RidePrice= Trip::where('id',$request->trip_id)->first();
                    $com=$total/$driverPrice->commission * 100;
                    $newtotal=$total-$com;
                    $RidePrice->app_cut = $newtotal;
                    $RidePrice->driver_cut = $com;
                    $RidePrice->save();
                }else{
                    return response()->json(['message' => 'Ride Had Been Completed']);
                }
            }

        }

        public function ScheduleTrip(Request $request,$id){

            if ($request->data == 0 ) {
                $lng = $request->lng;
                $lat = $request->lat;

                $Requesteduser=\Auth::user();

                $Resturant_id=Restaurants::where('parent_user',$Requesteduser)->first();


                if(empty($lng) || empty($lng)){
                return response()->json(['code' => 105, 'message' => 'no near by found']);
                }
                // $Drivers = User::withDistance($lat, $lng)->with('MyCar')
                //     ->where('id', '!=', Auth::user()->id)
                //     ->where(function ($q) {
                //         $q->whereNotNull('lat')->orWhereNotNull('lng');
                // });

                $Drivers=User::whereHas('DriverSpacliy', function($q){
                $q->where('drivers_specialty_id', '=',5)->orWhere('drivers_specialty_id', '=',22)->orWhere('drivers_specialty_id', '=',6);
                })->get();

                if ($Drivers->count() <= 0) {
                return response()->json(['code' => 105, 'message' => 'no near by found']);
                }

                $NewItem= new Trip;
                $NewItem->client_id = Auth::user()->id;
                $NewItem->from_lat = $request->from_lat;
                $NewItem->from_lng = $request->from_lng;
                $NewItem->to_lat = $request->to_lat;
                $NewItem->to_lng = $request->to_lng;
                $NewItem->address_from = $request->address_from;
                $NewItem->address_to = $request->address_to;
                $NewItem->ride_type	 = 40;
                $NewItem->total = $request->total;
                $NewItem->order_id = $id;
                $NewItem->save();


                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);

                $notificationBuilder = new PayloadNotificationBuilder('The Best');
                $notificationBuilder->setBody('You have trip request')
                                ->setSound('default');

                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(['lat' => $lat,
                                    'lng' => $lng,
                                    'user_id' => $Resturant_id->id,
                                    'name' => $Resturant_id->name,
                                    'phone' => $Resturant_id->phone,
                                    'trip_id' => $NewItem->id,

                ]);

                $option = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $data = $dataBuilder->build();

                // $token = User::where('id',$id)->pluck('fcm_token')->first();
                $tokens = $Drivers->pluck('fcm_token')->toArray();

                $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

                return response()->json(['massage' => 'Please wait for driver approval']);
            } else {
                $ScheduleTrip= Trip::where('id',$id)->first();
                $ScheduleTrip->schedule_at=Carbon::now()->addMinutes($request->date);
                $ScheduleTrip->save();
                return response()->json(['message' => 'Done']);
            }



            }






    }
