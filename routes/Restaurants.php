<?php


Route::group(['prefix' => 'Auth'], function () {
    Route::post('register','Restaurants\RestaurantAuthController@Register');
    Route::post('login','Restaurants\RestaurantAuthController@Login');
    Route::get('allCities','Restaurants\RestaurantApisController@allCities');
    Route::get('DistrictsById/{id}','Restaurants\RestaurantApisController@DistrictsById');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'Main'], function () {
    Route::post('GetDistanceRest','Restaurants\RestaurantApisController@GetDistanceRest');

    Route::get('AllCategories','Api\MainController@AllCategories');
    Route::get('CategoryById/{id}','Api\MainController@CategoryById');
    Route::get('OldOrders','Restaurants\RestaurantApisController@OldOrders'); 
    Route::get('NewOrders','Restaurants\RestaurantApisController@NewOrders');
    Route::post('ChangeOrderStatus/{id}','Restaurants\RestaurantApisController@ChangeOrderStatus');
    Route::get('CancelOrder/{id}','Restaurants\RestaurantApisController@CancelOrder');

    Route::post('RestaurantsReports','Restaurants\RestaurantApisController@RestaurantsReports');
    Route::get('MenuItems/{id}','Api\MainController@MenuItems');
    Route::get('MyMenus','Restaurants\RestaurantApisController@MyMenus');
    Route::get('MyPlace','Restaurants\RestaurantApisController@MyPlace');
    Route::post('UpdateProfile','Restaurants\RestaurantApisController@UpdateProfile');

    Route::get('AddtionalItems','Restaurants\RestaurantApisController@AddtionalItems');

    Route::post('AddProduct','Restaurants\RestaurantApisController@AddProduct');
    Route::post('DeleteProduct/{id}','Restaurants\RestaurantApisController@DeleteProduct');
    Route::post('UpdateProduct/{id}','Restaurants\RestaurantApisController@UpdateProduct');

    Route::post('AddMenu','Restaurants\RestaurantApisController@AddMenu');
    Route::post('UpdateMenu/{id}','Restaurants\RestaurantApisController@UpdateMenu');
    Route::post('DeleteMenu/{id}','Restaurants\RestaurantApisController@DeleteMenu');

    




});

});



