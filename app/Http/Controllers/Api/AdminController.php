<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use Geocoder;
use App\Models\Cafes;
use App\Models\Cities;
use App\Models\CafeMenu;
use App\Models\CarModels;
use App\Models\Districts;
use App\Models\SpecialCar;
use App\Model\CafeCategory;
use App\Models\Restaurants;
use App\Models\RoadService;
use App\Models\MenuCategory;
use App\Models\AdditionalItem;
use Illuminate\Http\Request;
use App\Models\MainCategories;
use App\Models\RestaurantMenu;
use App\Models\CarsRegistration;
use App\Models\RestaurantCategory;
use App\Http\Controllers\Controller;
use App\Notifications\CarArriveNotification;

class AdminController extends Controller
{

    public function Cities(){
        $Cities=Cities::all();
        return response()->json(['status' => '200','Cities' => $Cities]);
    }
    public function Districts($id){
        $Districts=Districts::where('id',$id)->get();
        return response()->json(['status' => '200','Districts' => $Districts]);
    }

    public function addestaurantCategory(Request $request){

        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(RestaurantCategory::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new RestaurantCategory;
        $flight->name = $request->name;
        $flight->image = $image;
        $flight->type_id = $request->type_id;
        $flight->save();

        return response()->json(['status' => '200',
        'type' => $flight,
        'message' => 'Post unsaved successfully']);

    }

    public function AddResturant(Request $request){

        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(Restaurants::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new Restaurants;
        $flight->name = $request->name;;
        $flight->category_id = $request->category_id;
        $flight->description = $request->description;
        $flight->address = $request->address;
        $flight->image = $image;
        $flight->type_id = $request->type_id;
        $flight->lat = $request->lat;
        $flight->lng = $request->lng;
        $flight->country = $request->country;
        $flight->government = $request->government;
        $flight->district = $request->district;



        $flight->delivery_price = $request->delivery_price;


        $flight->save();

        User::find(Auth::user()->id)->notify(new CarArriveNotification);
    }

    public function AddResturantMenu(Request $request){

        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(RestaurantMenu::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new RestaurantMenu;
        $flight->name = $request->name;;
        $flight->price = $request->price;
        $flight->description = $request->description;
        $flight->restaurant_id = $request->restaurant_id;
        $flight->menu_category_id = $request->menu_category_id;

        $flight->image = $image;
        $flight->save();

        return response()->json(['status' => '200',
        'type' => $flight,
        'message' => 'Post unsaved successfully']);

    }

    public function addCafeCategory(Request $request){

        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(CafeCategory::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new CafeCategory;
        $flight->name = $request->name;
        $flight->image = $image;
        $flight->save();

        return response()->json(['status' => '200',
        'type' => $flight,
        'message' => 'Post unsaved successfully']);

    }

    public function AddCafe(Request $request){

        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(Cafes::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new Cafes;
        $flight->name = $request->name;;
        $flight->category_id = $request->category_id;
        $flight->description = $request->description;
        $flight->address = $request->address;
        $flight->image = $image;
        $flight->save();
    }

    public function AddCafeMenu(Request $request){

        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(CafeMenu::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new CafeMenu;
        $flight->name = $request->name;;
        $flight->price = $request->price;
        $flight->description = $request->description;
        $flight->cafe_id = $request->cafe_id;
        $flight->image = $image;
        $flight->save();

        return response()->json(['status' => '200',
        'type' => $flight,
        'message' => 'Post unsaved successfully']);

    }

    public function MenuCategorys(Request $request){

        $flight = new MenuCategory;
        $flight->name = $request->name;;
        $flight->restaurant_id = $request->restaurant_id;
        $flight->save();

        return response()->json(['status' => '200',
        'type' => $flight,
        'message' => 'Post unsaved successfully']);

    }

    public function CarsRegistration(Request $request){
        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(MainCategories::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }
        $flight = new CarsRegistration;
        $flight->car_number = $request->car_number;
        $flight->car_model = $request->car_model;
        $flight->user_id = $request->user_id;
        $flight->car_type_id = $request->car_type_id;
        $flight->type = $request->type;

        $flight->image = $image;
        $flight->save();
    }

    public function addSpecialCar(Request $request){
        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(CarsRegistration::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }
        $flight = new SpecialCar;
        $flight->name = $request->name;
        $flight->image = $image;
        $flight->save();
    }


    public function addCategory(Request $request){
        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(MainCategories::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new MainCategories;
        $flight->name = $request->name;
        $flight->image = $image;

        $flight->save();
    }

    public function addRoadServ(Request $request){
        $image = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = md5(time() . str_random(16)) . '.' . $request->file('image')->extension();
            try {
                $request->file('image')->storeAs(RoadService::IMAGE_FOLDER, $image);
            } catch (\Exception $e) {
                return response()->json(['code' => 102, 'message' => $e->getMessage()]);
            }
        }

        $flight = new RoadService;
        $flight->name = $request->name;
        $flight->image = $image;

        $flight->save();
    }



    public function test(Request $request){


        $articles = User::whereHas('DriverSpacliy', function ($query) {
            $query->where('drivers_specialty_id', '=', '2');
        })->get();

        return response()->json(['status' => '200',
        'articles' => $articles,
        'message' => 'Post unsaved successfully']);

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

    public function indexcars(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = CarModels::query();

        // if no restaurant_id has been selected, show no options
        if (! $form['car_factories_id']) {
            return [];
        }

        // if a restaurant_id has been selected, only show MenuCategorys in that restaurant_id
        if ($form['car_factories_id']) {
            $options = $options->where('car_factories_id', $form['car_factories_id']);
        }

        if ($search_term) {
            $results = $options->where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $options->paginate(10);
    }

    public function showcars($id)
    {
        return CarModels::find($id);
    }

    public function indexAdditionalItem(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = AdditionalItem::where('name_ar', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = AdditionalItem::paginate(10);
        }

        return $results;
    }
    public function showAdditionalItem($id)
    {
        return AdditionalItem::find($id);
    }

    public function indexRestaurantCategory(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = RestaurantCategory::where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        }
        else
        {
            $results = RestaurantCategory::paginate(10);
        }

        return $results;
    }

    public function showRestaurantCategory($id)
    {
        return RestaurantCategory::find($id);
    }


    public function RlaceBysId($id){
        $restaurant_id=Restaurants::where('id',$id)->with('MenuesCategories','RestaurantCategory')->first();
        return response()->json(['items' => $restaurant_id]);
    }



    public function indexctiys(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Districts::query();

        // if no restaurant_id has been selected, show no options
        if (! $form['government']) {
            return [];
        }

        // if a restaurant_id has been selected, only show MenuCategorys in that restaurant_id
        if ($form['government']) {
            $options = $options->where('city_id', $form['government']);
        }

        if ($search_term) {
            $results = $options->where('name', 'LIKE', '%'.$search_term.'%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $options->paginate(10);
    }

    public function showctiys($id)
    {
        return Districts::find($id);
    }





}
