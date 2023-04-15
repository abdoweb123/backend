<?php

namespace App\Http\Controllers\Api;

use App\Models\Restaurants;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\RestaurantMenu;
use App\Models\RestaurantCategory;
use App\Http\Controllers\Controller;

class RestaurantController extends Controller
{

    public function RestaurantByCategory($id){
        $Restaurants=Restaurants::where('category_id',$id)->where('type_id',1)->get();
        return response()->json(['Restaurants' => $Restaurants]);
    }
    
    public function RestaurantByid($id){
        $Restaurants=Restaurants::where('id',$id)->where('type_id',1)->with('MenuesCategories')->get();
        return response()->json(['Restaurants' => $Restaurants]);
    }

    public function RestaurantCategory(){
        $RestaurantsCateory=RestaurantCategory::paginate();
        return response()->json(['RestaurantsCateory' => $RestaurantsCateory]);
    }

    public function MenuItems($id){
        $RestaurantMenu=RestaurantMenu::where('menu_category_id',$id)->get();
        return response()->json(['RestaurantMenu' => $RestaurantMenu]);
    }
    
    



}
