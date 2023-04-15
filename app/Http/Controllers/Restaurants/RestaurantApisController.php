<?php

namespace App\Http\Controllers\Restaurants;

use Auth;
use App\User;
use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Order;
use App\Models\Cities;
use App\Models\Districts;
use App\Traits\SaveImage;
use App\Models\CarSetting;
use App\Models\Restaurants;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\AdditionalItem;
use App\Models\RestaurantMenu;
use App\Http\Resources\MyOrders;
use App\Http\Controllers\Controller;
use App\Models\RestaurantmenuAdditional;

class RestaurantApisController extends Controller
{  

    use SaveImage;

    public function MyMenus(){
        $Resturant_id=Restaurants::where('parent_user',Auth::user()->id)->pluck('id')->first();
        $MyMenus=MenuCategory::where('restaurant_id',$Resturant_id)->get();
        return response()->json(['status'=>200,'MyMenus' => $MyMenus]);
    }

    
    public function OldOrders(){
        $Resturant_id=Restaurants::where('parent_user',Auth::user()->id)->pluck('id')->first();

        $OldOrders = Order::where('user_parent',$Resturant_id)
                        ->where('status','done')
                        ->orderBy('created_at', 'desc')
                        ->with('OrderItems')->paginate();

                        $collection = $OldOrders->getCollection();

                        $collection =  $collection->map(function ($m) {
                            $m->trip = Trip::where('order_id',$m->id)->first();
                            return $m;
                        });
                        $OldOrders->setCollection($collection);

        if($OldOrders->count()<=0){
            return response()->json(['status'=>200,'OldOrders' => 'There are no Old Orders']);
        }else {
            return response()->json(['status' => '200', 
            'OldOrders' => $collection]);
        }
    }

    public function NewOrders(){
        $Resturant_id=Restaurants::where('parent_user',Auth::user()->id)->pluck('id')->first();
        
        $NewOrders = Order::where('user_parent', Auth::user()->id)
                        ->where('status','pending')
                        ->orderBy('created_at', 'desc')
                        ->with('OrderItems')->paginate();
                
                    $collection = $NewOrders->getCollection();

                    $collection =  $collection->map(function ($m) {
                        $m->trip = Trip::where('order_id',$m->id)->first();
                        
                        return $m;
                    });
                    $NewOrders->setCollection($collection);
                    return new MyOrders($collection);



            return response()->json(['status' => '200', 
            'NewOrders' => $NewOrders]);
        
    }

    public function RestaurantsReports(Request $request){
    $to_date=Carbon::parse($request->to_date);
    $from_date=Carbon::parse($request->from_date);
    $to_dates = $to_date->addDays(1);

    $MyRange = Order::where('user_parent',Auth::user()->id)
    ->whereBetween('created_at', array($from_date, $to_dates))
    ->with('OrderItems')->get();


    $MyOrdersCount = Order::where('user_parent',Auth::user()->id)
    ->whereBetween('created_at', array($from_date, $to_dates))->count();

    $MyOrdersDone = Order::where('user_parent',Auth::user()->id)->where('status','done')
    ->whereBetween('created_at', array($from_date, $to_dates))->count();

    $MyOrdersCanceled = Order::where('user_parent',Auth::user()->id)->where('status','canceled')
    ->whereBetween('created_at', array($from_date, $to_dates))->count();

    $MyOrdersMoney = Order::where('user_parent',Auth::user()->id)->where('status','done')
    ->whereBetween('created_at', array($from_date, $to_dates))->sum('total');

                return [
                    'MyOrders' => new MyOrders($MyRange),
                    'MyOrdersCount' => $MyOrdersCount,
                    'MyOrdersDone' => $MyOrdersDone,
                    'MyOrdersCanceled' => $MyOrdersCanceled,
                    'MyOrdersMoney' => $MyOrdersMoney,
                ];



}

    public function ChangeOrderStatus(Request $request,$id){
        $Update = Order::find($id);
        $Update->status = $request->status;
        $Update->save();
        return response()->json(['status' => '200', 
        'massage' => 'You Change The Order Successfully']);
    
    }

    public function CancelOrder($id){
        $Update = Order::find($id);
        $Update->status = 'canceled';
        $Update->save();
        return response()->json(['status' => '200', 
        'massage' => 'You canceled The Order Successfully']);
    
    }

    

    //*Menus Apis

    public function AddMenu(Request $request){
        $Resturant_id=Restaurants::where('parent_user',Auth::user()->id)->pluck('id')->first();
        $flight = new MenuCategory;
        $flight->name = $request->name;;
        $flight->restaurant_id = $Resturant_id;
        $flight->cat_id = 1;
        $flight->save();
        return response()->json(['status' => '200', 
        'Menu' => $flight]);
    }
    public function UpdateMenu(Request $request,$id){
        // $Resturant_id=Restaurants::where('parent_user',Auth::user()->id)->pluck('id')->first();

        $flight = MenuCategory::find($id);
        $flight->name = $request->name;
        $flight->save();
        return response()->json(['status' => '200', 
        'message' => 'Menu updated successfully',
        'Menu' => $flight]);
    }
    
    public function DeleteMenu($id){
        $flight = MenuCategory::find($id);
        $flight->delete();
        return response()->json(['status' => '200', 
        'message' => 'Menu deleted successfully',
        ]);

    }



    public function MyPlace(){
        $Resturant_id=Restaurants::where('parent_user',Auth::user()->id)->pluck('id')->first();
        $MyRestaurant=Restaurants::where('id',$Resturant_id)->with('MenuesCategories')->first();
        return response()->json(['MyRestaurant' => $MyRestaurant]);
    }

    //**Product Apis

    public function AddProduct(Request $request)
    {
        $flight = new RestaurantMenu;
        $flight->preventAttrSet = true;
        $flight->name = $request->name_ar;
        $flight->name_en = $request->name_en;
        $flight->price = $request->price;
        $flight->image = $file_name = $this->saveImage($request->image, 'public/restaurants');
        $flight->description = $request->description_ar;
        $flight->description_en = $request->description_en;
        $flight->restaurant_id = $request->restaurant_id;
        $flight->menu_category_id = $request->menu_category_id;

        $flight->attribute_title= $request->attribute_title_ar_one;
        $flight->attribute_title_en= $request->attribute_title_en_one;
        $flight->attribute_body= $request->attribute_body;

        $flight->attribute_title_two= $request->attribute_title_ar_two;
        $flight->attribute_title_en_two= $request->attribute_title_en_two;
        $flight->attribute_body_two= $request->attribute_body_two;


        $flight->attribute_title_three= $request->attribute_title_ar_three;
        $flight->attribute_title_en_three= $request->attribute_title_en_three;
        $flight->attribute_body_three= $request->attribute_body_three;

        $flight->cat_id= $request->cat_id;
        $flight->save();
        if ($flight->save()) {
            if($request->has('additional_id')){

                foreach($request->additional_id as $additional_id) {

                    $additional = new RestaurantmenuAdditional;
                    $additional->restaurant_menu_id = $flight->id;
                    $additional->additional_item_id = $additional_id;
                    $additional->save();

                
                }
            }
        }


        return response()->json(['status' => '200', 
        'product' => $flight, 
        'message' => 'product saved successfully']);
    }

    public function UpdateProduct(Request $request,$id){
        $user=RestaurantMenu::where('id',$id)->first();

        $user->preventAttrSet = true;
        if ($request->has('name_ar')) {
            $user->name = $request->name_ar;
        }
        if ($request->has('name_en')) {
            $user->name_en = $request->name_en;
        }
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('price')) {
            $user->price = $request->price;
        }
        if ($request->has('cat_id')) {
            $user->cat_id = $request->cat_id;
        }
        if ($request->has('description')) {
            $user->description = $request->description;
        }
        if ($request->has('description_en')) {
            $user->description_en = $request->description_en;
        }
        if ($request->has('menu_category_id')) {
            $user->menu_category_id = $request->menu_category_id;
        }
        if ($request->has('menu_category_id')) {
            $user->menu_category_id = $request->menu_category_id;
        }
        if ($request->has('attribute_title_ar_one')) {
            $user->attribute_title = $request->attribute_title_ar_one;
        }
        if ($request->has('attribute_title_en_one')) {
            $user->attribute_title_en = $request->attribute_title_en_one;
        }
        if ($request->has('attribute_body')) {
            $user->attribute_body = $request->attribute_body;
        }
        if ($request->has('attribute_title_ar_two')) {
            $user->attribute_title_two = $request->attribute_title_ar_two;
        }
        if ($request->has('attribute_title_en_two')) {
            $user->attribute_title_en_two = $request->attribute_title_en_two;
        }
        if ($request->has('attribute_body_two')) {
            $user->attribute_body_two = $request->attribute_body_two;
        }
        if ($request->has('attribute_title_ar_three')) {
            $user->attribute_title_three = $request->attribute_title_ar_three;
        }
        if ($request->has('attribute_title_en_three')) {
            $user->attribute_title_en_three = $request->attribute_title_en_three;
        }
        if ($request->has('attribute_body_three')) {
            $user->attribute_body_three = $request->attribute_body_three;
        }
        if ($request->has('image')) {
            $user->image =  $file_name = $this->saveImage($request->image, 'public/restaurants');
        }
        
        $user->save();


        return response()->json(['status' => '200', 
        'product' => $user, 
        'message' => 'product updated successfully']);



    }

    public function DeleteProduct($id)
    {
        $flight = RestaurantMenu::find($id);
        $flight->delete();
        return response()->json(['status' => '200', 
        'message' => 'Product deleted successfully',
        ]);

    }

    public function allCities(){
        $cities=Cities::all();
        return response()->json(['status' => '200', 
        'Cities' => $cities, 
        ]);
    }
    public function DistrictsById($id){
        $Districts=Districts::where('city_id',$id)->get();
        return response()->json(['status' => '200', 
        'Districts' => $Districts, 
        ]);
    }   

    public function AddtionalItems(){
        $AdditionalItem=AdditionalItem::all();
        return response()->json(['status' => '200', 
        'AdditionalItem' => $AdditionalItem, 
        ]);
    }

    public function UpdateProfile(Request $request){

        $user = Restaurants::where('parent_user',Auth::user()->id)->first();
        $user->preventAttrSet = true;

        if ($user == null) {
            return response()->json(['status' => '200', 
            'massage' => 'you do not have permission',
            ]);
        }else {
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('name_en')) {
                $user->name_en = $request->name_en;
            }
            if ($request->has('category_id')) {
                $user->category_id = $request->category_id;
            }
            if ($request->has('delivery_price')) {
                $user->delivery_price = $request->delivery_price;
            }
            if ($request->has('address')) {
                $user->address = $request->address;
            }
            if ($request->has('address_en')) {
                $user->address_en = $request->address_en;
            }
            if ($request->has('description')) {
                $user->description = $request->description;
            }
            if ($request->has('description_en')) {
                $user->description_en = $request->description_en;
            }
            if ($request->has('government')) {
                $user->government = $request->government;
            }
            if ($request->has('district')) {
                $user->district = $request->district;
            }
            if ($request->has('lat')) {
                $user->lat = $request->lat;
            }
            if ($request->has('lng')) {
                $user->lng = $request->lng;
            }
            if ($request->has('place_owner_name')) {
                $user->place_owner_name = $request->place_owner_name;
            }
            if ($request->has('place_email')) {
                $user->place_email = $request->place_email;
            }
            if ($request->has('place_phone')) {
                $user->place_phone = $request->place_phone;
            }
            if ($request->has('order_limit')) {
                $user->order_limit = $request->order_limit;
            }
            if ($request->has('branches')) {
                $user->branches = $request->branches;
            }
            if ($request->has('working_hours')) {
                $user->working_hours = $request->working_hours;
            }
            if ($request->has('time_frame')) {
                $user->time_frame = $request->time_frame;
            }
            if ($request->has('responsibles')) {
                $user->responsibles = $request->responsibles;
            }
            if ($request->has('bank_info')) {
                $user->bank_info = $request->bank_info;
            }
            if ($request->has('image')) {
                $user->image =   $file_name = $this->saveImage($request->image, 'public/restaurants');

            }
            $user->save();
    
            return response()->json(['status' => '200', 
            'massage' => 'Updated successfully',
            'Restaurants' => $user, 
            ]);
        }
    }
    function GetDistanceRest(Request $request){
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
        $est=$final*1.5;
    
        $costpramter= CarSetting::where('id',1)->pluck('byrequest')->first();
    
    
        return response()->json(['distance' => $final,
        'Cost' => $final*$costpramter/1000,
        'est' => $est
        ]);
    
    }
}
