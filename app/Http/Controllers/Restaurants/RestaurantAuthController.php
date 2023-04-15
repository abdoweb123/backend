<?php

namespace App\Http\Controllers\Restaurants;

use Auth;
use App\User;
use App\Models\Rolemob;
use App\Traits\SaveImage;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RestaurantAuthController extends Controller
{
    use SaveImage;

    public function Register(Request $request){
        $user= new User;
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= bcrypt($request->password);
        if($user->save()){
            $NewItem= new Restaurants;
            $NewItem->preventAttrSet = true;

            $NewItem->name = $request->name_ar;
            $NewItem->name_en = $request->name_en;
            $NewItem->image =   $file_name = $this->saveImage($request->imagere, 'public/restaurants');
            $NewItem->description = $request->description;
            $NewItem->description_en = $request->description_en;
            $NewItem->address = $request->address;
            $NewItem->address_en = $request->address_en;
            $NewItem->category_id = $request->category_id;
            $NewItem->delivery_price = $request->delivery_price;
            $NewItem->lat = $request->lat;
            $NewItem->lng = $request->lng;
            $NewItem->type_id = $request->type_id;
            $NewItem->government = $request->government;
            $NewItem->parent_user = $user->id;
            $NewItem->district = $request->district;
            $NewItem->place_owner_name = $request->place_owner_name;
            $NewItem->ownerimage = $file_name = $this->saveImage($request->ownerimage, 'public/restaurants');
            $NewItem->imgcert = $file_name = $this->saveImage($request->imgcert, 'public/restaurants');
            $NewItem->place_email = $request->place_email;
            $NewItem->signatureimage = $this->saveImage($request->signatureimage, 'public/restaurants');
            $NewItem->place_phone = $request->place_phone;
            $NewItem->order_limit = $request->order_limit;
            $NewItem->branches = $request->branches;
            $NewItem->working_hours = $request->working_hours;
            $NewItem->time_frame = $request->time_frame;
            $NewItem->responsibles = $request->responsibles;
            $NewItem->parent_user=$user->id;
            $NewItem->save();
                $role= new Rolemob;
                $role->role_id = 3;
                $role->model_type = 'App\User';
                $role->model_id = $user->id;
                $role->save();

            $accessToken=$user->createToken('TheBest')->accessToken;
            return response()->json([
                'massege'=>'You Are Register successfully',
                'Restaurant'=>$NewItem,
                'accessToken'=>$accessToken,

            ]);
        }

    }



    public function Login(Request $request){
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
        $user = auth()->user();
        $UpdateItem= User::find($user->id);
        $UpdateItem->fcm_token = $request->fcm_token;
        $UpdateItem->save();

        $Myresturant=Restaurants::where('parent_user',$user->id)->first();
        $accessToken= auth()->user()->createToken('TheBest')->accessToken;
        return response()->json([
            'Myresturant'=> $Myresturant,
            'accessToken' => $accessToken,
        ]);

     }
}
