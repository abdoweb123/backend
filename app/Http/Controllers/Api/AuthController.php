<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Traits\SaveImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{  
    use SaveImage;

    public function register(Request $request){

        $user = new User();
        $user->preventAttrSet = true;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->lat = $request->input('lat');
        $user->lng = $request->input('lng');
        $user->phone = $request->input('phone');
        $user->fcm_token = $request->input('fcm_token');
        $user->nationality = $request->input('nationality');
        $user->birth_date= $request->input('birth_date');
        $user->save();

        $accessToken=$user->createToken('Thebest')->accessToken;
          return response()->json([
              'massege'=>'You Are Register successfully',
              'user'=>$user,
              'accessToken'=>$accessToken
          ]);

    }

    public function Login(Request $request){

        $PhoneNumber = $request->input('phone');
        $fcm_token = $request->input('fcm_token');


        $user = User::where('phone',$PhoneNumber)->first();// Something like User:: where() or whatever depending on your impl.

        if($user)  {
            
            $UpdateItem= User::find($user->id);
            $UpdateItem->fcm_token = $request->fcm_token;
            $UpdateItem->save();

            Auth::login($user);
            $accessToken= auth()->user()->createToken('Thebest')->accessToken;

            return response()->json([
                'user'=> auth()->user(),
                'accessToken' => $accessToken
            ]);
        }else {
            return response()->json([
            'message' => 'This Phone Number Is Not Used Before']);
        }

        // $request->validate([
        //     'phone' => 'required',
        //     // 'password' => 'required',
        // ]);
        // $credentials = request(['phone']);
        // if(!Auth::attempt($credentials))
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 401);

        //  $user = $request->user();

        // $accessToken= auth()->user()->createToken('Thebest')->accessToken;
        // return response()->json([
        //     'user'=> auth()->user(),
        //     'accessToken' => $accessToken
        // ]);
    }
}
