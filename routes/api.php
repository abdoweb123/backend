<?php


Route::group(['prefix' => 'Auth'], function () {
    Route::post('register','Api\AuthController@register');
    Route::post('login','Api\AuthController@Login');

});

Route::get('Cities','Api\AdminController@Cities');
Route::get('Districts/{id}','Api\AdminController@Districts');


Route::get('indexmenecats','Api\AdminController@indexmenecats');
Route::get('showmenecats/{id}','Api\AdminController@showmenecats');

Route::get('indexcars','Api\AdminController@indexcars');
Route::get('showcars/{id}','Api\AdminController@showcars');

Route::get('indexRestaurantCategory', 'Api\AdminController@indexRestaurantCategory');
Route::get('showRestaurantCategory/{id}', 'Api\AdminController@showRestaurantCategory');

Route::get('indexAdditionalItem', 'Api\AdminController@indexAdditionalItem');
Route::get('showAdditionalItem/{id}', 'Api\AdminController@showAdditionalItem');


Route::get('indexctiys','Api\AdminController@indexctiys');
Route::get('showctiys/{id}','Api\AdminController@showctiys');

Route::get('RlaceBysId/{id}','Api\AdminController@RlaceBysId');



Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'Main'], function () {
        Route::get('AllCategories','Api\MainController@AllCategories');
        Route::get('CategoryById/{id}','Api\MainController@CategoryById');
        Route::get('RlaceByCategory/{id}','Api\MainController@RlaceByCategory');
        Route::get('RlaceById/{id}','Api\MainController@RlaceById');
        Route::get('MenuItems/{id}','Api\MainController@MenuItems');
        Route::post('nearByMarkets','Api\MainController@nearByMarkets');
        Route::post('MarketsFilter','Api\MainController@MarketsFilter');
        Route::get('AllMarkets','Api\MainController@AllMarkets');
        Route::get('markettypes','Api\MainController@markettypes');

    });

    Route::group(['prefix' => 'shabra'], function () {
        Route::get('AllShabra','Api\MainController@AllShabra');
        Route::get('Shabratypes','Api\MainController@Shabratypes');
        Route::post('nearByShera','Api\MainController@nearByShera');
        Route::post('nearByShabra','Api\MainController@nearByShabra');
        Route::post('ShabraFilter','Api\MainController@ShabraFilter');
        Route::get('RlaceById/{id}','Api\MainController@RlaceById');
        Route::get('MenuItems/{id}','Api\MainController@MenuItems');
    });


    Route::group(['prefix' => 'User'], function () {
        Route::get('user-by-id/{id}','Api\UserController@UserByID');
        Route::post('updateProfile','Api\UserController@updateProfile');
        Route::get('DriverById/{id}','Driver\DriverApisController@GetDriverById');

    });

    Route::group(['prefix' => 'Notes'], function () {
        Route::get('GetNote','Api\UserController@GetNote');
        Route::post('AddNote','Api\UserController@AddNote');
    });

    Route::group(['prefix' => 'subscriptions'], function () {

        Route::get('SubscriptionsTypes','Api\SubscriptionsController@SpecialCarTypes');
        Route::get('RequerdEquipments','Api\SubscriptionsController@RequerdEquipment');


        Route::post('NewSubscription','Api\SubscriptionsController@NewSubscription');
        Route::get('MySubscriptions','Api\SubscriptionsController@MySubscriptions');
        Route::post('ConfirmRideSubscription','Api\SubscriptionsController@ConfirmRide');
        Route::post('GetDistanceSubscription','Api\SubscriptionsController@haversineGreatCircleDistance');
        Route::post('StartRideSubscription','Api\SubscriptionsController@StartRide');
        Route::get('CancelRideSubscription','Api\SubscriptionsController@CancelRide');
        Route::get('EndRideSubscription','Api\SubscriptionsController@EndRide');
        Route::post('RidePriceSubscription','Api\SubscriptionsController@RidePrice');


    });
    Route::group(['prefix' => 'Me'], function () {
        Route::get('MyProfile','Api\MeController@MyProfile');
        Route::get('MyNotifiy','Api\MeController@MyUnreadNotification');
    });

    Route::group(['prefix' => 'Car'], function () {
        Route::get('GetCars','Api\CarsController@GetCars');
        Route::post('NearestCars','Api\CarsController@NearestCars');
        Route::post('ConfirmRideCar','Api\CarsController@ConfirmRide');
        Route::post('GetDistanceCar','Api\CarsController@haversineGreatCircleDistance');
        Route::post('StartRideCar','Api\CarsController@StartRide');
        Route::get('CancelRideCar','Api\CarsController@CancelRide');
        Route::get('EndRideCar','Api\CarsController@EndRide');
        Route::post('RidePriceCar','Api\CarsController@RidePrice');
    });


    Route::group(['prefix' => 'SpecialCars'], function () {
        Route::get('SpecialCarTypes','Api\SpecialCarsController@SpecialCarTypes');
        Route::get('RequerdEquipment','Api\SpecialCarsController@RequerdEquipment');

        Route::post('GetSpecialCar','Api\SpecialCarsController@GetSpecialCar');
        Route::post('NearestSpCars','Api\SpecialCarsController@NearestCars');
        Route::post('ConfirmRideSpCar','Api\SpecialCarsController@ConfirmRide');
        Route::post('GetDistanceSpCar','Api\SpecialCarsController@haversineGreatCircleDistance');
        Route::post('StartRideSpCar','Api\SpecialCarsController@StartRide');
        Route::get('CancelRideSpCar','Api\SpecialCarsController@CancelRide');
        Route::get('EndRideSpCar','Api\SpecialCarsController@EndRide');
        Route::post('RidePriceSpCar','Api\SpecialCarsController@RidePrice');

    });

    Route::group(['prefix' => 'Truck'], function () {

        Route::get('TruckCarTypes','Api\TruckController@TruckCarTypes');
        Route::post('GetFurnitureCar','Api\TruckController@GetFurnitureCar');

        Route::post('GetTruck','Api\TruckController@GetSpecialCar');
        Route::post('NearestTruck','Api\TruckController@NearestCars');
        Route::post('ConfirmRideTruck','Api\TruckController@ConfirmRide');
        Route::post('GetDistanceTruck','Api\TruckController@haversineGreatCircleDistance');
        Route::post('StartRideTruck','Api\TruckController@StartRide');
        Route::get('CancelRideTruck','Api\TruckController@CancelRide');
        Route::get('EndRideTruck','Api\TruckController@EndRide');
        Route::post('RidePriceTruck','Api\TruckController@RidePrice');
    });



    Route::group(['prefix' => 'Driver'], function () {
        Route::get('near-by','Api\DriverController@nearBy');

    });
    Route::group(['prefix' => 'Order'], function () {
        Route::post('store','Api\OrderController@store');
        Route::get('MyOrders/{id}','Api\OrderController@MyOrders');
    });
    Route::group(['prefix' => 'RoadService'], function () {
        Route::post('store','Api\RoadServiceController@store');
        Route::get('RoadServCategories','Api\RoadServiceController@RoadServCategories');
        Route::get('RoadServOptions/{id}','Api\RoadServiceController@RoadServOptions');
        Route::post('ConfirmRideRs','Api\RoadServiceController@ConfirmRide');
        Route::post('GetDistanceRs','Api\RoadServiceController@haversineGreatCircleDistance');
        Route::post('StartRideRs','Api\RoadServiceControlapler@StartRide');
        Route::get('CancelRideRs','Api\RoadServiceController@CancelRide');
        Route::get('EndRideRs','Api\RoadServiceController@EndRide');
        Route::post('RidePriceRs','Api\RoadServiceController@RidePrice');
        Route::post('NearestRs','Api\RoadServiceController@NearestCars');


    });

    Route::group(['prefix' => 'Trip'], function () {
        Route::get('MyTripsForClient/{id}','Api\TripController@MyTripsForClient');
        Route::get('MyTripsForDriver','Api\TripController@MyTripsForDriver');
        Route::post('GetTaxi','Api\TripController@GetTaxi');
        Route::post('ChangeOrderTrip/{id}','Api\TripController@ChangeOrderTrip');
        Route::post('ScheduleTrip/{id}','Api\TripController@ScheduleTrip');
        Route::post('ConfirmRide','Api\TripController@ConfirmRide');
        Route::post('GetDistance','Api\TripController@haversineGreatCircleDistance');
        Route::post('StartRide/{id}','Api\TripController@StartRide');
        Route::get('CancelRide','Api\TripController@CancelRide');
        Route::get('EndRide/{id}','Api\TripController@EndRide');
        Route::post('RidePrice','Api\TripController@RidePrice');

    });

    Route::group(['prefix' => 'Restaurants'], function () {
        Route::post('AddResturant','Api\AdminController@AddResturant');
        Route::post('AddResturantMenu','Api\AdminController@AddResturantMenu');
        Route::post('RestaurantCategorys','Api\AdminController@addestaurantCategory');
        Route::post('MenuCategorys','Api\AdminController@MenuCategorys');

        Route::post('addCategory','Api\AdminController@addCategory');
        Route::post('addRoadServ','Api\AdminController@addRoadServ');
        Route::post('test','Api\AdminController@test');





        Route::post('addSpecialCar','Api\AdminController@addSpecialCar');
        Route::post('AddCafe','Api\AdminController@AddCafe');

        Route::post('AddCafeMenu','Api\AdminController@AddCafeMenu');
        Route::post('addCafeCategory','Api\AdminController@addCafeCategory');
        Route::post('CarsRegistration','Api\AdminController@CarsRegistration');



    });





});
