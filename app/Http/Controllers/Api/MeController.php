<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeController extends Controller
{
    public function MyProfile(){
    $MyProfile=User::where('id',Auth::user()->id)->with('MyCar')->first();

    $CurrnetTrip=Trip::where('client_id',Auth::user()->id)->where('status','!=','Completed')
    ->orWhere('status','!=','Canceled')->orderBy('created_at', 'DESC')->first();

    if($CurrnetTrip == null){

        return response()->json(['status'=>200,
        'MyProfile'=>$MyProfile,
        'Trips' => 'There are no Trip at the moment']);
        }

    return response()->json([
            'status'=>200,
            'MyProfile'=>$MyProfile,
            'CurrnetTrip'=>$CurrnetTrip,

        ]);
    }

    public function MyUnreadNotification(){
        $MyProfle=User::where('id',Auth::user()->id)->first();
        $user = Auth::user();
        $Mynotifications=$user->notifications;
        return response()->json(['MyNotifiy' => $Mynotifications]);
    }
}
