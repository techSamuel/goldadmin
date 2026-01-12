<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiApiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = Setting::where('key', 'gemini_api_key')->value('value');
    }

    public function analyzeImages(array $imagePaths)
    {
        $model = Setting::where('key', 'gemini_model')->value('value') ?? 'gemini-2.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        if (empty($this->apiKey)) {
            return ['success' => false, 'message' => 'Gemini API Key is missing in Settings'];
        }

        try {
            $parts = [];

            // Add Prompt
            // ... (keep logic) ...
            $parts[] = [
                'text' => "You are an expert jewelry appraiser. Analyze these images of gold ornaments and/or hallmark papers.
                
                OUTPUT INSTRUCTIONS:
                1.  Language: **Bengali (Bangla)**.
                2.  Format: return a JSON object with two main parts:
                    - 'result_text': A friendly assessment paragraph + bullet points (as before).
                    - 'summary': A short object with key details:
                        - 'type': Short item name (e.g. 'সোনার আংটি').
                        - 'purity': Purity level (e.g. '22K', '21K', 'Hallmarked').
                        - 'weight': Estimated weight only if visually clear, else 'অজানা'.
                
                Example JSON:
                {
                    \"result_text\": \"...full text here...\",
                    \"summary\": {
                        \"type\": \"সোনার হার\",
                        \"purity\": \"২২ ক্যারেট\",
                        \"weight\": \"১০ গ্রাম\"
                    }
                }
                
                Return ONLY valid JSON. No markdown."
            ];

            // Add Images
            foreach ($imagePaths as $path) {
                $imageData = base64_encode(file_get_contents($path));
                $mimeType = mime_content_type($path);

                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $mimeType,
                        'data' => $imageData
                    ]
                ];
            }

            $payload = [
                'contents' => [
                    [
                        'parts' => $parts
                    ]
                ]
            ];

            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                        'Content-Type' => 'application/json'
                    ])->post("{$url}?key={$this->apiKey}", $payload);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'Unknown API Error';
                return ['success' => false, 'message' => "Gemini API Failed: $errorMessage"];
            }

            $result = $response->json();
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Clean JSON (remove markdown code blocks if present)
            $text = preg_replace('/^```json\s*|\s*```$/', '', $text);
            $data = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['success' => true, 'raw_text' => $text, 'message' => 'Analysis complete but JSON parsing failed'];
            }

            return ['success' => true, 'data' => $data];

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
