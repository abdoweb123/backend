<?php



Route::group(['prefix' => 'Auth'], function () {
    Route::post('register','Driver\DriverAuthController@Register');
    Route::post('login','Driver\DriverAuthController@Login');
});
Route::get('DriversSpecialty','Driver\DriverApisController@DriversSpecialty');
Route::get('test','Driver\DriverApisController@test');


Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'Main'], function () {
    Route::get('Myprofile','Driver\DriverApisController@Myprofile');
    Route::get('Country','Driver\DriverApisController@AllCountry');
    Route::post('UpdateDriver','Driver\DriverApisController@UpdateDriver');
    Route::post('ChnageSpecialty','Driver\DriverApisController@ChnageSpecialty');
    Route::post('AddSpecialty','Driver\DriverApisController@AddSpecialty');
    Route::post('DeleteSpecialty/{id}','Driver\DriverApisController@DeleteSpecialty');
    Route::get('IndexSpecialty','Driver\DriverApisController@IndexSpecialty');



    Route::post('DriverLocation','Driver\DriverApisController@DriverLocation');
    Route::post('Myorders','Driver\DriverApisController@Myorders');
    Route::post('AcceptTrip/{id}','Driver\DriverApisController@AcceptTrip');
    Route::post('DriverArrived/{id}','Driver\DriverApisController@DriverArrived');

    Route::get('DriverById/{id}','Driver\DriverApisController@GetDriverById');
    Route::post('DriverReports','Driver\DriverApisController@DriverReports');
    Route::get('GetTripByID/{id}','Driver\DriverApisController@GetTripByID');
    Route::get('YourDriverHere/{id}','Driver\DriverApisController@YourDriverHere');
    Route::post('ConformEndRide/{id}','Driver\DriverApisController@ConformEndRide');

    });
});

