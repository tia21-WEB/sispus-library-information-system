<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    public static function send($token, $title, $body)
    {
        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            storage_path('app/firebase/firebase-service-account.json')
        );

        $accessToken = $credentials
            ->fetchAuthToken()['access_token'];

$response = Http::withToken($accessToken)
    ->post(
        'https://fcm.googleapis.com/v1/projects/sispus-a9c3a/messages:send',
        [
            "message" => [
                "token" => $token,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ]
            ]
        ]
    );

logger()->info('FCM RESPONSE', [
    'status' => $response->status(),
    'body' => $response->body(),
]);
    }
}