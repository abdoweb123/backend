<?php

namespace App\Traits;

use FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

Trait  SendFcm
{
     function SendFcmToPerson($body,$data,$UserToken){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        
        $notificationBuilder = new PayloadNotificationBuilder('The Best');
        $notificationBuilder->setBody($body)
                            ->setSound('default');
        
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);
        
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        
        $token = $UserToken;
        
        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
    }

    


}