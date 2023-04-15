<?php

namespace App\Http\Controllers\Api;

use FCM;
use Auth;

use App\User;

use App\Models\Trip;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Models\OrderAttribute;
use App\Models\RestaurantMenu;
use App\Http\Resources\MyOrders;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;


class OrderController extends Controller
{

    public function store(Request $request){
        $user = Auth::user();

        $restaurant_id= RestaurantMenu::where('id',$request->product_id)->pluck('restaurant_id')->first();
        $restaurant_user=Restaurants::where('id',$restaurant_id)->pluck('parent_user')->first();

        $order= new Order;
        $order->username = $user->name;
        $order->user_id = $user->id;
        $order->lat = $request->lat;
        $order->lng = $request->lng;
        $order->address = $request->address;
        $order->phone = $request->phone;
        $order->total = $request->total;
        $order->comment = $request->comment;
        $order->cat_id = $request->cat_id;
        $order->user_parent = $restaurant_user;
        $order->restaurant_id = $restaurant_id;

        if ( $order->save()) {
            if($request->has('product_id')){
                for ($i = 0; $i < count($request->product_id); $i++) {
                    $answers[] = [
                        'item_id' => $request->product_id[$i],
                        'place_id' => RestaurantMenu::where('id',$request->product_id[$i])->pluck('restaurant_id')->first(),
                        'count' => $request->count[$i],
                        'attribute_body'=> $request->attribute_body[$i],
                        'attribute_body_two'=> $request->attribute_body_two[$i],
                        'attribute_body_three'=> $request->attribute_body_three[$i],
                        'additional'=> $request->additional[$i],
                        'order_id' => $order->id,
                    ];
                }
                OrderItem::insert($answers);
    $restaurant_id= RestaurantMenu::where('id',$request->product_id)->pluck('restaurant_id')->first();
    $restaurantc=Restaurants::where('id',$restaurant_id)->first();

                $Trip= new Trip;
                $Trip->client_id = Auth::user()->id;
                $Trip->from_lat = $restaurantc->lat;
                $Trip->from_lng = $restaurantc->lng;
                $Trip->to_lat	 = $request->lat;
                $Trip->to_lng = $request->lng;
                $Trip->order_id =  $order->id;
                $Trip->save();



    $user=User::where('id',$restaurantc->parent_user)->pluck('fcm_token')->first();
    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60*20);
    $notificationBuilder = new PayloadNotificationBuilder('The Best');
    $notificationBuilder->setBody('You have Order request')
                    ->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['restaurant_id' => $restaurant_id,

    ]);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

    $token =  $user;

    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

                return response()->json([
                'answers' => $answers]);

            }

        }
    }

    public function MyOrders($id)
    {
    $user = Auth::user();



     $MyOrders=Order::where('user_id',$user->id)->where('cat_id',$id)->with('OrderItems')->orderBy('created_at', 'desc')->paginate();

    // return response()->json(['status' => '200',
    // 'MyOrders' => $MyOrders ]);

        $collection = $MyOrders->getCollection();

        $collection =  $collection->map(function ($m) {
            $m->trip = Trip::where('order_id',$m->id)->first();
            return $m;
        });
        $MyOrders->setCollection($collection);

        return new MyOrders($collection);

    }
}
