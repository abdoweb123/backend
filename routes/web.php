<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\Subscription;

// use LaravelFCM\Message\OptionsBuilder;
// use LaravelFCM\Message\PayloadDataBuilder;
// use LaravelFCM\Message\PayloadNotificationBuilder;
// use FCM;


Route::get('/', function () {
    return view('welcome');

});

Route::get('/tag',function(){

    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60*20);

    $notificationBuilder = new PayloadNotificationBuilder('EarthMood');
    $notificationBuilder->setBody('Share With Us What is You Mood Now')
                        ->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['a_data' => 'my_data']);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

    $token = "eyJhbGciOiJSUzI1NiIsImtpZCI6IjE2NzUwM2UwYWVjNTJkZGZiODk2NTIxYjkxN2ZiOGUyMGMxZjMzMDAiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL3NlY3VyZXRva2VuLmdvb2dsZS5jb20vdGhlYmVzdC05OWJhNiIsImF1ZCI6InRoZWJlc3QtOTliYTYiLCJhdXRoX3RpbWUiOjE2MDE2NjIwMTMsInVzZXJfaWQiOiJ5YjBiY0N1NzV4WU0xYlY4WFRtQWdmaGN4ZnQxIiwic3ViIjoieWIwYmNDdTc1eFlNMWJWOFhUbUFnZmhjeGZ0MSIsImlhdCI6MTYwMTY2MjAxNCwiZXhwIjoxNjAxNjY1NjE0LCJwaG9uZV9udW1iZXIiOiIrMjAxMDYyNzM0MDkwIiwiZmlyZWJhc2UiOnsiaWRlbnRpdGllcyI6eyJwaG9uZSI6WyIrMjAxMDYyNzM0MDkwIl19LCJzaWduX2luX3Byb3ZpZGVyIjoicGhvbmUifX0.jP-hFffCRGc7PK0ZhN0JNJNwrcleia0m-_PbIE9fj40sUlEOTYpjYkP_H8GF7ME6N51cDn9b7WM8ZwGnisTc4d_moBhj3hQhzR-rIEoX2B9aPBSnXXBkbC2uRHMOp6wouDV-rI4q64-F5N5089vP5J5qFQcydr8DNb12OLUzXb4XqAYsuLBTOjbunj3uIlNWAoeUP9Rb1fDCD8hGaPQFzw6PFJi4mMyZuZK_Jq0YCjq-A9L0wZ3nr5uycXHqog6pG0Ndja5-0OnWGUSxe00IVCatqpNC4GBE0WAMp93KYNjJmbrokyyh_i0hotq7yIyzkaQtdbbauf1ECeKIn_MGzA";

    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

    $downstreamResponse->numberSuccess();
    $downstreamResponse->numberFailure();
    $downstreamResponse->numberModification();

    dd($downstreamResponse);

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
