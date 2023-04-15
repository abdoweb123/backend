<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Model\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


class DriverTripRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Trip $trip)
    {
        if ($trip->driver_id != $trip->client_id) {
            try {
                $Fcm_token = User::where('id',$trip->driver_id)->pluck()->frist();
                $notification = Notification::create([
                    'model' => $trip,
                    'key' => 'Posttrip' . $trip->driver_id . $trip->client_id,
                    'member_id' => $trip->driver_id,
                    'is_read' => 0,
                    'fcm_token' => $Fcm_token,
                    'fcm_status' => '',
                ]);

                $token = User::where('id',$trip->driver_id)->pluck()->frist();

                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
        
                $notificationBuilder = new PayloadNotificationBuilder('The Best');
                $notificationBuilder->setBody('You Have Trip Request')
                                    ->setSound('default');
        
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(['a_data' => 'my_data']);
        
                $option = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $data = $dataBuilder->build();
                
                $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
                
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
    }

}
