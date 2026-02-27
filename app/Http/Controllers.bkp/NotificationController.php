<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Fmcdevice;
use App\Models\Setting;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{

    public function saveToken(Request $request)
    {

        $token = Fmcdevice::where('token', $request->token)->first();
        if (!$token) {
            Fmcdevice::create([
                'user_id' => auth()->user()->id,
                'token' => $request->token,
                'device' => $request->device
            ]);
        }
        return response()->json(['token saved successfully.']);
    }

    public function sendNotification(Request $request)
    {
        $setting = Setting::first();
        $firebase = new FirebaseService();

        $firebaseTokens = User::where('id', $request->user_id)->first()->tokens()->pluck('token');
        //dd($firebaseTokens);
        $accessToken = $firebase->getAccessToken();

        $responses = [];
        foreach ($firebaseTokens as $firebaseToken) {
            $response = Http::withToken($accessToken)
                ->post('https://fcm.googleapis.com/v1/projects/cashnex-ce3e1/messages:send', [
                    "message" => [
                        "token" => $firebaseToken ?? null,
                        "notification" => [
                            "title" => $request->title,
                            "body" => $request->body,
                        ],
                        "android" => [
                            "notification" => [
                                "icon" => "https://app.cashnex.com.br/storage/avatars/NCHm5DyVNIgB8S74jXxB73zI5pUZcirrf90H5NWy.png",//url('/storage' . $setting->favicon_light),
                                "color" => $setting->software_color
                            ]
                        ],
                        "webpush" => [
                            "notification" => [ // <- aqui vai o icon para web
                                "title" => $request->title,
                                "body" => $request->body,
                                "icon" => "https://app.cashnex.com.br/storage/avatars/NCHm5DyVNIgB8S74jXxB73zI5pUZcirrf90H5NWy.png",//url('/storage' . $setting->favicon_light),
                                "badge" => "https://app.cashnex.com.br/storage/avatars/NCHm5DyVNIgB8S74jXxB73zI5pUZcirrf90H5NWy.png",//url('/storage' . $setting->favicon_light) // opcional
                            ],
                            "fcm_options" => [
                                "link" => env('APP_URL')
                            ]
                        ]
                    ]
                ]);
            $responses[] = $response->json();
        }



        return $responses;


    }

    public function firebaseMessagingSw()
    {
        $setting = Setting::first();
        $favicon = asset('storage'.$setting->favicon_light); // URL dinâmica
        $fcm = Helper::getFCM();
        $projectId = $fcm['projectId']; 
        $apiKey = $fcm['apiKey']; 
        $appId = $fcm['appId']; 
        $messageSenderId = $fcm['messageSenderId']; 
        $measurementId = $fcm['measurementId']; 
        $authDomain = $fcm['authDomain']; 
        $storageBucket = $fcm['storageBucket']; 

        $content = <<<JS
        importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
        importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

        firebase.initializeApp({
            apiKey: '{$apiKey}',
            authDomain: '{$authDomain}',
            projectId: '{$projectId}',
            storageBucket: '{$storageBucket}',
            messagingSenderId: '{$messageSenderId}',
            appId: '{$appId}',
            measurementId: '{$measurementId}'
        });

        const messaging = firebase.messaging();
        messaging.setBackgroundMessageHandler(function(payload) {
            const notificationTitle = payload.notification.title;
            const notificationOptions = {
                body: payload.notification.body,
                icon: '{$favicon}'
            };
            return self.registration.showNotification(notificationTitle, notificationOptions);
        });
        JS;

        return response($content, 200)
            ->header('Content-Type', 'application/javascript');
    }
}
