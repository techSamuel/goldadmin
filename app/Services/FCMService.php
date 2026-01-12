<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FCMService
{
    // protected $url = 'https://fcm.googleapis.com/fcm/send'; // Legacy
    protected $projectId;

    public function sendNotification($title, $body, $topic = 'all', $imageUrl = null)
    {
        $serviceAccountJson = Setting::where('key', 'firebase_service_account')->value('value');

        if (empty($serviceAccountJson)) {
            Log::error('FCM Service Account JSON is missing in Settings.');
            return ['success' => false, 'message' => 'FCM Service Account (V1) not configured'];
        }

        try {
            $credentials = json_decode($serviceAccountJson, true);
            if (!isset($credentials['project_id'])) {
                throw new \Exception('Invalid Service Account JSON: project_id missing');
            }
            $this->projectId = $credentials['project_id'];

            // Get Access Token
            $sa = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/firebase.messaging',
                $credentials
            );
            $accessToken = $sa->fetchAuthToken()['access_token'];

            // V1 Endpoint
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            // V1 Payload Structure
            $payload = [
                'message' => [
                    'topic' => $topic,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    // Android specific priority
                    'android' => [
                        'priority' => 'HIGH',
                        'notification' => [
                            'sound' => 'default'
                        ]
                    ],
                    // Custom data for Flutter
                    'data' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'status' => 'done',
                        'type' => 'general',
                    ]
                ]
            ];

            if ($imageUrl) {
                $payload['message']['notification']['image'] = $imageUrl;
                $payload['message']['android']['notification']['image'] = $imageUrl;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'body' => $response->json(),
            ];

        } catch (\Exception $e) {
            Log::error('FCM V1 Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
