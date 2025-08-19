<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FcmHelper
{
    public static function sendNotification($token, $title, $body, $image = null)
    {
        $serverKey = env('FCM_SERVER_KEY'); // Ambil dari Firebase console

        $payload = [
            "to" => $token,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "image" => $image,
            ],
            "data" => [
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "status" => "done",
            ],
        ];

        $response = Http::withHeaders([
            "Authorization" => "key={$serverKey}",
            "Content-Type" => "application/json",
        ])->post("https://fcm.googleapis.com/fcm/send", $payload);

        return $response->json();
    }
}

