<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Appearance
            ['key' => 'app_name', 'value' => 'Gold Calculator', 'group' => 'appearance'],
            ['key' => 'primary_color', 'value' => '#FFD700', 'group' => 'appearance'],
            ['key' => 'secondary_color', 'value' => '#1F2937', 'group' => 'appearance'],

            // Contact
            ['key' => 'contact_email', 'value' => 'support@goldcalc.com', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+880170000000', 'group' => 'contact'],
            ['key' => 'website_url', 'value' => 'https://goldcalc.com', 'group' => 'contact'],

            // System
            ['key' => 'firebase_service_account', 'value' => '', 'group' => 'system'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'group' => 'system'],
            ['key' => 'announcement', 'value' => 'Welcome to Gold Calculator App! Get daily price updates.', 'group' => 'system'],

            // AdMob
            ['key' => 'admob_enabled', 'value' => 'false', 'group' => 'admob'],
            ['key' => 'admob_app_id', 'value' => '', 'group' => 'admob'],
            ['key' => 'admob_banner_id', 'value' => '', 'group' => 'admob'],
            ['key' => 'admob_interstitial_id', 'value' => '', 'group' => 'admob'],
            ['key' => 'admob_video_id', 'value' => '', 'group' => 'admob'],

            // Legal
            ['key' => 'privacy_policy', 'value' => '<h1>Privacy Policy</h1><p>Welcome to our Gold Calculator app...</p>', 'group' => 'legal'],

            // Automation
            ['key' => 'gold_api_provider', 'value' => 'metalpriceapi', 'group' => 'automation'],
            ['key' => 'gold_api_key', 'value' => '', 'group' => 'automation'],
            ['key' => 'gold_adjustment_percentage', 'value' => '0', 'group' => 'automation'], // Percentage to add (e.g. 5 for 5%)

            // AI Configuration
            ['key' => 'gemini_api_key', 'value' => '', 'group' => 'ai'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
