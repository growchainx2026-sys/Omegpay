<?php

namespace App\Services;

use App\Models\Fcm;
use App\Models\Setting;
use App\Models\User;
use Google\Client;
use Exception;
use Http;

class FirebaseService
{
    public function getAccessToken(): string
    {
        
        $client = new Client();
        $client->setAuthConfig(storage_path('app/private/certificados/firebase-service-account.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();

        if (!isset($token['access_token'])) {
            return null;
        }

        return $token['access_token'];
    }

    public function sendNotification(User $user, $valor)
    {
        try {
            $setting = Setting::first();
            $fcm = Fcm::first();

            $body = $fcm->body;

            $newvalor = 'R$ ' . number_format($valor, 2, ',', '.');
            $body = str_replace('{valor}', $newvalor, $body);

            $firebaseTokens = $user->tokens()->pluck('token');
            $accessToken = $this->getAccessToken();

            if(is_null($accessToken)) return true;

            $responses = [];
            foreach ($firebaseTokens as $firebaseToken) {
                $response = Http::withToken($accessToken)
                    ->post('https://fcm.googleapis.com/v1/projects/cashnex-ce3e1/messages:send', [
                        "message" => [
                            "token" => $firebaseToken ?? null,
                            "notification" => [
                                "title" => $fcm->title,
                                "body" => $body,
                            ],
                            "android" => [
                                "notification" => [
                                    "icon" => url('/storage' . $setting->favicon_light),
                                    "color" => $setting->software_color
                                ]
                            ],
                            "webpush" => [
                                "notification" => [
                                    "title" => $fcm->title,
                                    "body" => $body,
                                    "icon" => url('/storage' . $setting->favicon_light),
                                    "badge" => url('/storage' . $setting->favicon_light)
                                ],
                                "fcm_options" => [
                                    "link" => env('APP_URL')
                                ]
                            ]
                        ]
                    ]);
                $responses[] = $response->json();
            }

            \Log::debug('[FCM][SEND][NOTIFICATION][RESPONSE]: ' . json_encode($responses));
            return $responses;

        } catch (\Throwable $e) {
            // ⚠️ Captura QUALQUER erro (Exception, Error, etc.)
            \Log::error('[FCM][SEND][ERROR]: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Continua a execução do código abaixo normalmente
        }

    }
}
