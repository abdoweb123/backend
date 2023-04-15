<?php

namespace App\Http\Controllers\Driver;

use Auth;
use App\User;
use App\Traits\SaveImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class DriverAuthController extends Controller
{  
    use SaveImage;
    public function Register(Request $request){

        $user= new User;
        $user->preventAttrSet = true;
        $user->name= $request->name;
        $user->email= $request->email;  
        $user->password= bcrypt($request->password);
        $user->phone= $request->phone;
        // $user->image = $file_name = $this->saveImage($request->image, 'public/users');
        $user->fcm_token= $request->fcm_token;
        $user->lat= $request->lat;
        $user->lng= $request->lng;
        $user->birth_date= $request->birth_date;
        $user->status= 'WatingForApproval';
        // $user->imgcert=  $file_name = $this->saveImage($request->imgcert, 'public/users');
        $user->ssid_driver= $request->ssid_driver;
        // $user->ssidback= $file_name = $this->saveImage($request->ssidback, 'public/users');
        $user->ssidfront= $file_name = $this->saveImage($request->ssidfront, 'public/users');
        $user->address= $request->address;
        $user->passport=  $file_name = $this->saveImage($request->passport, 'public/users');
        $user->expierd_date= $request->expierd_date;
        $user->phone_intreal= $request->phone_intreal;
        $user->country_id= $request->country_id;
        $user->car_company_id= $request->car_company_id;
        $user->save();
        
        $accessToken=$user->createToken('TheBest')->accessToken;
        return response()->json([
            'massege'=>'You Are Register successfully',
            'User'=>$user,
            'accessToken'=>$accessToken
        ]);

    }
    
    
    public function Login(Request $request){
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
        $user = $request->user();
        
        $UpdateItem= User::find($user->id);
        $UpdateItem->fcm_token = $request->fcm_token;
        $UpdateItem->save();
        // $startDate = \Carbon\Carbon::createFromFormat('Y-m-d',$user->start_blocked_at);
        // $endDate = \Carbon\Carbon::createFromFormat('Y-m-d',$user->end_blocked_at);
        // $check = \Carbon\Carbon::now()->between($startDate,$endDate);
        // if ($check == true) {
        //     return response()->json([
        //         'message' => 'Sorry, your account has been banned. Please contact your account manager'
        //     ]);
        // } else {
        //     $accessToken= auth()->user()->createToken('TheBest')->accessToken;
        //     return response()->json([
        //         'user'=> auth()->user(),
        //         'accessToken' => $accessToken,
        //     ]);
        // }
        $accessToken= auth()->user()->createToken('TheBest')->accessToken;
        return response()->json([
            'user'=> auth()->user(),
            'accessToken' => $accessToken,
        ]);
 
     }




}
