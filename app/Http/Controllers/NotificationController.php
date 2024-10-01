<?php

namespace App\Http\Controllers;

use App\Services\RabbitMQService;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private $rabbitMQService;
    private $firebaseService;

    public function __construct(RabbitMQService $rabbitMQService, FirebaseService $firebaseService)
    {
        $this->rabbitMQService = $rabbitMQService;
        $this->firebaseService = $firebaseService;
    }

    public function publishMessage(Request $request)
    {
        $message = $request->only('identifier', 'type', 'deviceId', 'text');
        $this->rabbitMQService->publish($message);

        return response()->json(['status' => 'Message published to RabbitMQ']);
    }

    public function consumeMessages()
    {
        $this->rabbitMQService->consume(function($msg) {
            $data = json_decode($msg->body, true);

            if ($data && isset($data['identifier'], $data['deviceId'], $data['text'])) {
                // Send FCM
                $result = $this->firebaseService->sendNotification($data['deviceId'], $data['text']);
                
                if ($result) {
                    // Save to database
                    DB::table('fcm_jobs')->insert([
                        'identifier' => $data['identifier'],
                        'deliverAt' => now()
                    ]);

                    // Notify done
                    $this->rabbitMQService->publish([
                        'identifier' => $data['identifier'],
                        'deliverAt' => now()
                    ]);
                }
            }
        });
    }
}
