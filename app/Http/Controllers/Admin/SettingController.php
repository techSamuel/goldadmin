<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token']);

        // Define groups for keys (since we are creating them on the fly if missing)
        $groups = [
            'app_name' => 'appearance',
            'primary_color' => 'appearance',
            'secondary_color' => 'appearance',
            'contact_email' => 'contact',
            'contact_phone' => 'contact',
            'website_url' => 'contact',
            'firebase_service_account' => 'system',
            'maintenance_mode' => 'system',
            'admob_enabled' => 'admob',
            'admob_app_id' => 'admob',
            'admob_banner_id' => 'admob',
            'admob_interstitial_id' => 'admob',
            'admob_video_id' => 'admob',
            'admob_native_id' => 'admob',
            'privacy_policy' => 'legal',
            'gold_api_provider' => 'automation',
            'gold_api_key' => 'automation',
            'scraper_api_key' => 'automation', // New
            'gold_adjustment_percentage' => 'automation',
            'gemini_api_key' => 'ai',
            'gemini_model' => 'ai',
            'admin_panel_name' => 'branding',
            'admin_logo' => 'branding',
            'admin_primary_color' => 'branding', // Separate from app primary color if needed, or reuse
        ];

        foreach ($data as $key => $value) {
            $group = $groups[$key] ?? 'general';

            // Handle File Upload
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                // Store in public/storage/settings
                $path = $file->storeAs('settings', $filename, 'public');
                $value = '/storage/' . $path; // Save relative web path
            }

            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function importGoldHtml(Request $request)
    {
        $request->validate([
            'html_content' => 'required|string',
        ]);

        try {
            $service = new \App\Services\GoldPriceApiService();
            $result = $service->updateFromHtml($request->html_content);

            if ($result['success']) {
                return back()->with('success', 'Gold prices updated successfully from HTML!');
            } else {
                return back()->with('error', 'Failed to update: ' . ($result['message'] ?? 'Unknown Error'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Import Error: ' . $e->getMessage());
        }
    }
}
