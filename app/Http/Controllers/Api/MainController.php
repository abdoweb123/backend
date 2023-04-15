<?php

namespace App\Http\Controllers\Api;

use App\Models\Markets;
use App\Models\Restaurants;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\restaurant_id;
use App\Models\MainCategories;
use App\Models\RestaurantMenu;
use App\Models\RestaurantCategory;
use App\Http\Controllers\Controller;
use App\Models\Restaurantrestaurant_id;

class MainController extends Controller
{
    public function AllCategories(){
        $MainCategories=MainCategories::all();
        return response()->json(['MainCategories' => $MainCategories]);
    }

    public function CategoryById($id){
        $Restaurants=RestaurantCategory::where('type_id',$id)->get();
        return response()->json(['items' => $Restaurants]);
    }

    public function RlaceByCategory($id){
        $Restaurants = Restaurants::whereHas('RestaurantCategory', function ($query) use ($id) {
            $query->where('rest_cat.restaurant_category_id', $id);
          })->get();
        return response()->json(['items' => $Restaurants]);
    }

    public function restaurant_idById($id){
        $restaurant_id=Restaurantrestaurant_id::where('type_id',$id)->get();
        return response()->json(['items' => $restaurant_id]);
    }
    public function RlaceByrestaurant_id($id){
        $restaurant_id=restaurant_id::where('restaurant_id_id',$id)->get();
        return response()->json(['items' => $restaurant_id]);
    }
    public function RlaceById($id){
        $restaurant_id=Restaurants::where('id',$id)->with('MenuesCategories')->first();
        return response()->json(['items' => $restaurant_id]);
    }

    public function MenuItems($id){
        $RestaurantMenu=RestaurantMenu::where('menu_category_id',$id)->with('AdditionalItems')->get();

        return response()->json(['RestaurantMenu' => $RestaurantMenu,]);
    }

    public function nearByMarkets(Request $request)
    {
        $lng = $request->lng;
        $lat = $request->lat;

        if(empty($lng) || empty($lng)){
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
        $Drivers = Restaurants::withDistance($lat, $lng)
            ->where('type_id',8)->where('category_id',$request->category_id)
            ->where(function ($q) {
                $q->whereNotNull('lat')->orWhereNotNull('lng');
            });
        $Drivers = $Drivers->paginate(5);
        if ($Drivers->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
      //  $Drivers->setCollection($this->forceCollection($Drivers->getCollection()));
        return response()->json([
            'code' => 100,
            'paginate' => $Drivers
        ]);
    }

    public function MarketsFilter(Request $request){
        $Markets=Restaurants::where('type_id',8)
        ->where('country',$request->country)
        ->where('government',$request->government)
        ->where('district',$request->district)
        ->where('category_id',$request->category_id)
        ->get();
        if ($Markets->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no Market near you found']);
        }
        return response()->json([
            'code' => 100,
            'Markets' => $Markets
        ]);

    }

    public function AllMarkets(Request $request){
        $Markets=Restaurants::where('category_id',11)
        ->get();
        if ($Markets->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no Market near you found']);
        }
        return response()->json([
            'code' => 100,
            'Markets' => $Markets
        ]);

    }

    public function nearByShera(Request $request)
    {
        $lng = $request->lng;
        $lat = $request->lat;

        if(empty($lng) || empty($lng)){
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
        $Drivers = Restaurants::withDistance($lat, $lng)
            ->where('type_id',26)->where('category_id',26)
            ->where(function ($q) {
                $q->whereNotNull('lat')->orWhereNotNull('lng');
            });
        $Drivers = $Drivers->paginate(5);
        if ($Drivers->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no near by found']);
        }
      //  $Drivers->setCollection($this->forceCollection($Drivers->getCollection()));
        return response()->json([
            'code' => 100,
            'paginate' => $Drivers
        ]);
    }

    public function markettypes(){
        $Restaurants=RestaurantCategory::where('type_id',8)->get();
        return response()->json(['items' => $Restaurants]);
    }

    public function indexmenecats(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = MenuCategory::query();

        // if no restaurant_id has been selected, show no options
        if (! $form['restaurant_id']) {
            return [];
        }

        // if a restaurant_id has been selected, only show MenuCategorys in that restaurant_id
        if ($form['restaurant_id']) {
            $options = $options->where('restaurant_id', $form['restaurant_id']);
        }

        if ($search_term) {
            $results = $options->where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $options->paginate(10);
    }

    public function showmenecats($id)
    {
        return MenuCategory::find($id);
    }

    public function Shabratypes(){
        $Shabratypes=RestaurantCategory::where('type_id',20)->get();
        return response()->json(['Shabratypes' => $Shabratypes]);
    }

    public function ShabraFilter(Request $request){
        $Shabra=Restaurants::where('type_id',26)
        ->where('country',$request->country)
        ->where('government',$request->government)
        ->where('district',$request->district)
        ->where('category_id',$request->category_id)
        ->get();
        if ($Shabra->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no Shabra near you found']);
        }
        return response()->json([
            'code' => 100,
            'Shabra' => $Shabra
        ]);

    }

    public function AllShabra(Request $request){
        $Shabra=Restaurants::where('category_id',26)
        ->get();
        if ($Shabra->count() <= 0) {
            return response()->json(['code' => 105, 'message' => 'no Market near you found']);
        }
        return response()->json([
            'code' => 100,
            'Shabra' => $Shabra
        ]);

    }



}
