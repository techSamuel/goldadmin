<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AiAnalysisController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiApiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function analyze(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|min:1|max:5', // 1-4 ornaments + 1 hallmark
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB per image
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $imagePaths = [];

            // Process uploaded images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->getPathname(); // Temporary path is enough for immediate processing
                }
            }

            // Call AI Service
            $result = $this->geminiService->analyzeImages($imagePaths);

            if (!$result['success']) {
                return response()->json([
                    'message' => $result['message'],
                    'raw_response' => $result['raw_text'] ?? null
                ], 500);
            }

            return response()->json([
                'message' => 'Analysis successful',
                'data' => $result['data']
            ]);

        } catch (\Exception $e) {
            Log::error('AI Analysis Controller Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error during analysis'], 500);
        }
    }
}
