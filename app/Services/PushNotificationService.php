<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PushNotificationService
{

    public function sendPushNotifications($fcmToken, $title, $body)
    {
        try {

            $notification = [
                'title' => $title,
                'body' => $body,
            ];

            $payload = [
                'to' => $fcmToken,
                'notification' => $notification
            ];
            $fcm_server_key = config('fcm.token');
            // dd($fcm_server_key);
            $response = Http::acceptJson()->withToken($fcm_server_key)->post('https://fcm.googleapis.com/fcm/send', $payload);
            // dd($response);
            return $response;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    public function sendPushNotification($fcmToken, $title, $body)
    {
        $path = storage_path('app/firebase_credentials.json');
        $factory = (new Factory)->withServiceAccount($path);

        $messaging = $factory->createMessaging();

        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification(Notification::create($title,  $body));

        $response = $messaging->send($message);
        return $response;
    }
}
