<?php

namespace App\Http\Controllers\Driver;

use FCM;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Country;
use App\Models\DriverSep;
use Illuminate\Http\Request;
use App\Http\Resources\MyOrders;
use App\Models\DriversSpecialty;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;


    class DriverApisController extends Controller
    {

    public function Myprofile(){
    $MyProfile=User::where('id',Auth::user()->id)->with(['MyCar'])->first();

    $CurrnetTrip=Trip::where('driver_id',Auth::user()->id)->orderBy('created_at', 'DESC')->first();

    if($CurrnetTrip->status == 'Canceled' || $CurrnetTrip->status == 'Completed'){

        return response()->json(['status'=>200,
        'MyProfile'=>$MyProfile,
        'Trips' => 'There are no Trip at the moment']);
    }
    else {
        return response()->json([
            'status'=>200,
            'MyProfile'=>$MyProfile,
            'CurrnetTrip'=>$CurrnetTrip,

    ]);
    }


    }

    public function GetTripByID($id){

        $CurrnetTrip=Trip::where('id',$id)->first();

        if ($CurrnetTrip->ride_type == 4) {
            $spc=Trip::where('id',$id)->with('Trip_PrivteCars:trip_id,required_equipment,price_method,car_type,price_method_from,price_method_to')->get();

            $collection =  $spc->map(function ($m) {
                $m->ClientName = User::where('id',$m->client_id)->pluck('name')->first();
                $m->Clientphone = User::where('id',$m->client_id)->pluck('phone')->first();
                return $m;
            });

            return response()->json(['status'=>200,
            'Trip' => $collection]);

        }
        elseif ($CurrnetTrip->ride_type == 15 ) {
        $sccs=Trip::where('id',$id)->with('Trip_RoadService:trip_id,service_name,service_image,service_count,note,service_price,service_desc')->get();
        $collection =  $sccs->map(function ($m) {
            $m->ClientName = User::where('id',$m->client_id)->pluck('name')->first();
            $m->Clientphone = User::where('id',$m->client_id)->pluck('phone')->first();
            return $m;
        });
        return response()->json(['status'=>200,
            'Trip' => $collection]);
        }
        elseif ($CurrnetTrip->ride_type == 16) {
         $fffff=Trip::where('id',$id)->with('Trip_Furniture:trip_id,service_name,service_image,service_count,number_technicians,number_workers,note,date,time')->get();

        $collection =  $fffff->map(function ($m) {
            $m->ClientName = User::where('id',$m->client_id)->pluck('name')->first();
            $m->Clientphone = User::where('id',$m->client_id)->pluck('phone')->first();
            return $m;
        });
         return response()->json(['status'=>200,
                'Trip' => $collection]);
        }
        elseif ($CurrnetTrip->ride_type == 17) {
          $ssdd=Trip::where('id',$id)->with('Trip_Monthly:trip_id,from_time,to_time,working_days,going_coming,from_date,to_date,required_equipment')->get();
          $collection =  $ssdd->map(function ($m) {
            $m->ClientName = User::where('id',$m->client_id)->pluck('name')->first();
            $m->Clientphone = User::where('id',$m->client_id)->pluck('phone')->first();
            return $m;
        });
          return response()->json(['status'=>200,
                    'Trip' => $collection]);
        }
        else {
            $sr=Trip::where('id',$id)->get();
            $collection =  $sr->map(function ($m) {
                $m->ClientName = User::where('id',$m->client_id)->pluck('name')->first();
                $m->Clientphone = User::where('id',$m->client_id)->pluck('phone')->first();
                return $m;
            });
            return  response()->json(['status'=>200,'Trip' => $collection]);
        }

    }

    public function Myorders(){
        $MyOrders=Trip::where('driver_id',Auth::user()->id)->get();
        if($MyOrders->count()<=0){
            return response()->json(['status'=>200,'Trips' => 'There are no Trips']);
        }else {
            return response()->json([
                'status'=>200,
                'MyOrders'=>$MyOrders,
            ]);
        }
    }

    public function DriverLocation(Request $request){
        $user=User::where('id',Auth::user()->id)->first();
        $user->lat = $request->lat;
        $user->lng = $request->lng;
        $user->save();
        return response()->json([
            'status'=>200,
            'MyOrders'=>'Location updated succesfuly',
        ]);
    }


    public function DriverArrived(Request $request,$id){
        $Client=User::where('id',$id)->pluck('fcm_token')->first();
        // $UserToken= $Client->pluck('fcm_token');

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('The Best');
        $notificationBuilder->setBody('Your driver is here')
                        ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = $Client;

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return response()->json(['masseage' => 'Notification send']);


    }

    public function AcceptTrip(Request $request,$id){
        $lat=$request->lat;
        $lng=$request->lng;
        $driver_id=$request->driver_id;
        $trip_id=$request->trip_id;

        $Trip= Trip::find($trip_id);
        $Trip->driver_id =$driver_id;
        $Trip->save();


        $Client=User::where('id',$id)->pluck('fcm_token')->first();
        // $UserToken= $Client->pluck('fcm_token');


        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('The Best');
        $notificationBuilder->setBody('You Driver On His Way')
                        ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['lat' => $lat,
                            'lng' => $lng,
                            'driver_id' => $driver_id,
                            'trip_id' => $trip_id,
        ]);


        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // $token = User::where('id',$id)->pluck('fcm_token')->first();
        $tokens = $Client;

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return response()->json(['masseage' => 'Notification send']);


    }


    public function DriverReports(Request $request){
        $to_date=Carbon::parse($request->to_date);
        $from_date=Carbon::parse($request->from_date);
        $to_dates = $to_date->addDays(1);

        $MyTrips = Trip::where('driver_id',Auth::user()->id)
        ->whereBetween('created_at', array($from_date, $to_dates))
        ->get();

        $MyTripsCount = Trip::where('driver_id',Auth::user()->id)
        ->whereBetween('created_at', array($from_date, $to_dates))
        ->count();


        $MyTripsCanceled = Trip::where('driver_id',Auth::user()->id)->where('status','Canceled')
        ->whereBetween('created_at', array($from_date, $to_dates))
        ->count();

        $MyTripsCompleted = Trip::where('driver_id',Auth::user()->id)->where('status','Completed')
        ->whereBetween('created_at', array($from_date, $to_dates))
        ->count();

        $MyTripsMoney = Trip::where('driver_id',Auth::user()->id)->where('status','Completed')
        ->whereBetween('created_at', array($from_date, $to_dates))
        ->sum('total');

        if ($MyTripsMoney== null) {
            return response()->json(['status' => '200',
            'MyTrips' => $MyTrips,
            'MyTripsCount' => $MyTripsCount,
            'MyTripsCanceled' => $MyTripsCanceled,
            'MyTripsCompleted' => $MyTripsCompleted,
            'MyTripsMoney' => $MyTripsMoney,
            'MyHandleMoney' => 'There is not enough balance',
            ]);

        } else {
            $MyHandleMoney = $MyTripsMoney*80*100/100;
            return response()->json(['status' => '200',
            'MyTrips' => $MyTrips,
            'MyTripsCount' => $MyTripsCount,
            'MyTripsCanceled' => $MyTripsCanceled,
            'MyTripsCompleted' => $MyTripsCompleted,
            'MyTripsMoney' => $MyTripsMoney,
            'MyHandleMoney' => $MyHandleMoney,
            ]);
        }


    }

    public function DriversSpecialty(){
        $DriversSpecialty=DriversSpecialty::all();
        return response()->json(['status' => '200',
        'DriversSpecialty' => $DriversSpecialty
        ]);
    }

    public function ChnageSpecialty(Request $request){
        $DriversSpecialty=DriverSep::where('user_id',Auth::user()->id)->first();
        $DriversSpecialty->drivers_specialty_id = $request->drivers_specialty_id;
        $DriversSpecialty->save();
        return response()->json(['status' => '200',
        'masseage' => 'It has been successfully updated',
        ]);
    }

    public function DeleteSpecialty($id){
        $DriversSpecialty = DriverSep::where('id',$id)->where('user_id',Auth::user()->id)->first();
        $DriversSpecialty->delete();
        return response()->json(['status' => '200',
        'masseage' => 'Specialty Deleted Successfully ',
        ]);
    }

    public function IndexSpecialty(){
        $DriversSpecialty = DriverSep::where('user_id',Auth::user()->id)->paginate();
        return response()->json(['status' => '200',
        'DriversSpecialty' => $DriversSpecialty,
        ]);
    }

    public function AddSpecialty(Request $request){
        if($request->has('drivers_specialty_id')){
        foreach ($request->drivers_specialty_id as $drivers_specialty_id) {
            $NewItem= new DriverSep;
            $NewItem->drivers_specialty_id = $drivers_specialty_id;
            $NewItem->user_id = Auth::user()->id;
            $NewItem->save();
        }
        return response()->json(['status' => '200',
        'message' => 'Specialty saved successfully']);
        }
    }


    public function test(){

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('The Best');
        $notificationBuilder->setBody('Hello world')
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = "fvmaExtcSH6SFp2kv3Cc5i:APA91bH9ioSiN8I1do35GYtPIvo3s0RjA5xlmrF_dNjh8QVA_D2MZ5f78wf6hznCyK01yFl0ut-0iH_kzlFOtTQspIYOjMAtlVfvvfe7I3pQohCnMK4yVU5VJh5kZ3itAPUwFOlYvPQC";

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        return response()->json(['status' => '200',
        'downstreamResponse' => $downstreamResponse,
        'message' => 'Post unsaved successfully']);

    }

    public function YourDriverHere($id){

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('The Best');
        $notificationBuilder->setBody('Your driver has arrived')
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = User::where('id',$id)->pluck('fcm_token')->first();
        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        return response()->json(['status' => '200',
        'message' => 'Notification Send']);

    }

    public function ConformEndRide($id ,Request $request){
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
        $cost=$final*3;
        $est=$final*1.5;

        $flight = Trip::find($id);
        $flight->driver_comment = $request->driver_comment;
        $flight->status = $request->status;
        $flight->save();

        return response()->json(['status' => '200',
        'message' => 'Done']);
    }

    public function RidePrice(Request $request){

        $user=Auth::user();
        $RidePrice= Trip::where('driver_id',$user->id)->orderBy('created_at', 'desc')->first();
        $RidePrice->total = $request->total;
        $RidePrice->payment_method = $request->payment_method;
        $RidePrice->save();

        return response()->json(['message' => 'Done']);
    }

    public function UpdateDriver(Request $request){
        $user=User::where('id',Auth::user()->id)->first();

        $user->preventAttrSet = true;

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('image')) {
            $user->image = $file_name = $this->saveImage($request->image, 'public/users');
        }
        if ($request->has('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
        }
        if ($request->has('lat')) {
            $user->lat = $request->lat;
        }
        if ($request->has('lng')) {
            $user->lng = $request->lng;
        }
        if ($request->has('birth_date')) {
            $user->birth_date = $request->birth_date;
        }
        if ($request->has('image')) {
            $user->imgcert = $file_name = $this->saveImage($request->imgcert, 'public/users');
        }
        if ($request->has('ssid_driver')) {
            $user->ssid_driver = $request->ssid_driver;
        }
        if ($request->has('ssidback')) {
            $user->ssidback = $file_name = $this->saveImage($request->ssidback, 'public/users');
        }
        if ($request->has('ssidfront')) {
            $user->ssidback = $file_name = $this->saveImage($request->ssidfront, 'public/users');
        }
        if ($request->has('address')) {
            $user->address = $request->address;
        }
        if ($request->has('passport')) {
            $user->passport = $file_name = $this->saveImage($request->passport, 'public/users');
        }
        if ($request->has('phone_intreal')) {
            $user->phone_intreal = $request->phone_intreal;
        }
        if ($request->has('country_id')) {
            $user->country_id = $request->country_id;
        }
        if ($request->has('car_company_id')) {
            $user->car_company_id = $request->car_company_id;
        }


        $user->save();


        return response()->json(['status' => '200',
        'Driver' => $user,
        'message' => 'Driver updated successfully']);

    }


    public function AllCountry(){
        $Countries=Country::paginate();
        return response()->json(['status' => '200',
        'Countries' => $Countries, ]);

    }

    public function GetDriverById($id){
        $user=User::where('id',$id)->with('MyCar')->first();
        return response()->json(['status' => '200', 'Driver' => $user, ]);
    }








    }
