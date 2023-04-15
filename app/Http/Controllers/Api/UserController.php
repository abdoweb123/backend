<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Models\RestaurantNote;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function UserByID($id){
        $user=User::find($id);        
        return response()->json(['user' => $user]);
    }

    public function updateProfile(Request $request)
    {


        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(User::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $user = Auth::user();

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('birth_date')) {
            $user->birth_date = $request->birth_date;
        }

        if ($request->has('nationality')) {
            $user->nationality = $request->nationality;
        }


        if ($request->has('lat')) {
            $user->lat = $request->lat;
        }

        if ($request->has('lng')) {
            $user->lng = $request->lng;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        $old_image = null;
        if (!empty($image)) {
            $old_image = $user->getAttributes()['image'];
            $user->image = $image;
        }
        try {
            $user->save();

            return response()->json(['code' => 100, 'message' => 'saved successfully', 'item' => $user]);
        } catch (\PDOException $e) {
            return response()->json(['code' => 105, 'message' => $e->getMessage()]);
        }
    }
    public function AddNote (Request $request){
        $Note = new RestaurantNote;
        $Note->description = $request->description;
        $Note->price = $request->price;
        $Note->user_id = Auth::user()->id;
        $Note->save();
        return response()->json(['Note' => $Note]);

    }

    public function GetNote(){
        $Notes = RestaurantNote::where('user_id',Auth::user()->id)->get();
        return response()->json(['Notes' => $Notes]);
    }


}
