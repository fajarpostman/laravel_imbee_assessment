<?php

namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

class FirebaseService
{
    private $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(__DIR__.'/firebase_credentials.json');
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($deviceToken, $message)
    {
        $notification = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification([
                'title' => 'Incoming message',
                'body' => $message
            ]);

        return $this->messaging->send($notification);
    }
}