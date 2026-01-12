<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdMobConfig;
use Illuminate\Http\Request;

class AdMobController extends Controller
{
    // Admin methods removed (moved to Settings)


    // API: Fetch Config (Public)
    public function apiIndex()
    {
        // Fetch settings group 'admob'
        $settings = \App\Models\Setting::where('group', 'admob')->pluck('value', 'key');

        $isEnabled = ($settings['admob_enabled'] ?? 'false') === 'true';

        return response()->json([
            'success' => true,
            'data' => [
                'is_enabled' => $isEnabled,
                'banner' => $settings['admob_banner_id'] ?? null,
                'interstitial' => $settings['admob_interstitial_id'] ?? null,
                'rewarded' => $settings['admob_video_id'] ?? null,
                'native' => $settings['admob_native_id'] ?? null,
            ]
        ]);
    }
}
