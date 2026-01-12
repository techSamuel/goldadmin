@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">App Settings</h1>
            <p class="text-gray-600 mt-2">Manage dynamic configurations for your mobile app.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="settingsTab"
                    data-tabs-toggle="#settingsTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 text-blue-600 border-blue-600 active"
                            id="branding-tab" data-tabs-target="#branding" type="button" role="tab"
                            onclick="openTab(event, 'branding')">Branding</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            id="appearance-tab" data-tabs-target="#appearance" type="button" role="tab"
                            onclick="openTab(event, 'appearance')">App Appearance</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            id="contact-tab" data-tabs-target="#contact" type="button" role="tab"
                            onclick="openTab(event, 'contact')">Contact Info</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            id="system-tab" data-tabs-target="#system" type="button" role="tab"
                            onclick="openTab(event, 'system')">System & Credentials</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            id="admob-tab" data-tabs-target="#admob" type="button" role="tab"
                            onclick="openTab(event, 'admob')">AdMob Config</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            id="policy-tab" data-tabs-target="#policy" type="button" role="tab"
                            onclick="openTab(event, 'policy')">Privacy Policy</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            id="automation-tab" data-tabs-target="#automation" type="button" role="tab"
                            onclick="openTab(event, 'automation')">Automation</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button
                            class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            id="ai-tab" data-tabs-target="#ai" type="button" role="tab" onclick="openTab(event, 'ai')">AI
                            Configuration</button>
                    </li>
                </ul>
            </div>

            <div id="settingsTabContent" class="p-8">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @php
                        function getSetting($settings, $group, $key)
                        {
                            if (!isset($settings[$group])) {
                                return '';
                            }
                            return $settings[$group]->where('key', $key)->first()->value ?? '';
                        }
                    @endphp

                    <!-- Branding -->
                    <div id="branding" class="tab-pane block">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Admin Panel Branding</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Admin Panel Name</label>
                                <input type="text" name="admin_panel_name"
                                    value="{{ getSetting($settings, 'branding', 'admin_panel_name') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300"
                                    placeholder="Gold Admin">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Admin Logo</label>
                                @if(getSetting($settings, 'branding', 'admin_logo'))
                                    <div class="mb-2">
                                        <img src="{{ getSetting($settings, 'branding', 'admin_logo') }}"
                                            class="h-12 w-auto object-contain bg-gray-100 rounded p-1">
                                    </div>
                                @endif
                                <input type="file" name="admin_logo" accept="image/*"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                                <p class="text-xs text-gray-500 mt-1">Recommended height: 50px. Transparent PNG.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Appearance -->
                    <div id="appearance" class="tab-pane hidden">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Appearance</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">App Name</label>
                                <input type="text" name="app_name"
                                    value="{{ getSetting($settings, 'appearance', 'app_name') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Primary Color (Hex)</label>
                                <div class="flex items-center">
                                    <input type="color" value="{{ getSetting($settings, 'appearance', 'primary_color') }}"
                                        class="h-10 w-10 border rounded mr-2 p-1 bg-white cursor-pointer"
                                        onchange="document.getElementById('primary_color_text').value = this.value">
                                    <input type="text" id="primary_color_text" name="primary_color"
                                        value="{{ getSetting($settings, 'appearance', 'primary_color') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 uppercase">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Secondary Color (Hex)</label>
                                <div class="flex items-center">
                                    <input type="color" value="{{ getSetting($settings, 'appearance', 'secondary_color') }}"
                                        class="h-10 w-10 border rounded mr-2 p-1 bg-white cursor-pointer"
                                        onchange="document.getElementById('secondary_color_text').value = this.value">
                                    <input type="text" id="secondary_color_text" name="secondary_color"
                                        value="{{ getSetting($settings, 'appearance', 'secondary_color') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 uppercase">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div id="contact" class="tab-pane hidden">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Support Email</label>
                                <input type="email" name="contact_email"
                                    value="{{ getSetting($settings, 'contact', 'contact_email') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Support Phone / Whatsapp</label>
                                <input type="text" name="contact_phone"
                                    value="{{ getSetting($settings, 'contact', 'contact_phone') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Website URL</label>
                                <input type="url" name="website_url"
                                    value="{{ getSetting($settings, 'contact', 'website_url') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- System -->
                    <div id="system" class="tab-pane hidden">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">System Configurations</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-yellow-800">
                                <strong>Note:</strong> Sensitive keys like Firebase Server Key should be kept secret.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Firebase Service Account JSON (FCM
                                    V1)</label>
                                <p class="text-xs text-gray-500 mb-2">Paste the content of your service-account.json file
                                    here.</p>
                                <textarea name="firebase_service_account" rows="10"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-xs"
                                    placeholder="{ &quot;type&quot;: &quot;service_account&quot;, ... }">{{ getSetting($settings, 'system', 'firebase_service_account') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Maintenance Mode</label>
                                <select name="maintenance_mode"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                                    <option value="false" {{ getSetting($settings, 'system', 'maintenance_mode') == 'false' ? 'selected' : '' }}>Disabled (App is Live)</option>
                                    <option value="true" {{ getSetting($settings, 'system', 'maintenance_mode') == 'true' ? 'selected' : '' }}>Enabled (Show Maintenance Screen)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- AdMob Config -->
                    <div id="admob" class="tab-pane hidden">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">AdMob Configuration</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800">
                                <strong>Info:</strong> Manage your Google AdMob App ID and Unit IDs here.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Enable AdMob</label>
                                <select name="admob_enabled"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                                    <option value="true" {{ getSetting($settings, 'admob', 'admob_enabled') == 'true' ? 'selected' : '' }}>Enabled</option>
                                    <option value="false" {{ getSetting($settings, 'admob', 'admob_enabled') == 'false' ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">App ID</label>
                                <input type="text" name="admob_app_id"
                                    value="{{ getSetting($settings, 'admob', 'admob_app_id') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                                    placeholder="ca-app-pub-xxxxxxxxxxxxxxxx~yyyyyyyyyy">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Banner Unit ID</label>
                                <input type="text" name="admob_banner_id"
                                    value="{{ getSetting($settings, 'admob', 'admob_banner_id') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                                    placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Interstitial Unit ID</label>
                                <input type="text" name="admob_interstitial_id"
                                    value="{{ getSetting($settings, 'admob', 'admob_interstitial_id') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                                    placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Rewarded Video Unit ID</label>
                                <input type="text" name="admob_video_id"
                                    value="{{ getSetting($settings, 'admob', 'admob_video_id') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                                    placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Native Advanced Unit
                                        ID</label>
                                    <input type="text" name="admob_native_id"
                                        value="{{ getSetting($settings, 'admob', 'admob_native_id') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                                        placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy">
                                </div>
                            </div>
                        </div>

                    </div>

            </div>
        </div>

        <!-- Privacy Policy -->
        <div id="policy" class="tab-pane hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Privacy Policy Content</h3>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">HTML Content</label>
                    <p class="text-xs text-gray-500 mb-2">You can write raw HTML here. It will be rendered on the public
                        privacy
                        policy page.</p>
                    <textarea name="privacy_policy" rows="15"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm">{{ getSetting($settings, 'legal', 'privacy_policy') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ url('/privacy-policy') }}" target="_blank" class="text-blue-600 hover:underline">View Public
                    Page
                    &rarr;</a>
            </div>
        </div>

        <!-- Automation -->
        <div id="automation" class="tab-pane hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Gold Price Automation</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Info:</strong> Configure an API to automatically update gold prices.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">API Provider</label>
                    <select name="gold_api_provider"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                        <option value="metalpriceapi" {{ getSetting($settings, 'automation', 'gold_api_provider') == 'metalpriceapi' ? 'selected' : '' }}>MetalPriceAPI (Default)</option>
                        <option value="bajus" {{ getSetting($settings, 'automation', 'gold_api_provider') == 'bajus' ? 'selected' : '' }}>BAJUS Organization (Scraper)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">MetalPrice API Key (If using
                        MetalPriceAPI)</label>
                    <input type="password" name="gold_api_key"
                        value="{{ getSetting($settings, 'automation', 'gold_api_key') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                        placeholder="Enter MetalPrice API Key">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ScraperAPI Key (Optional - For Bajus
                        Automation)</label>
                    <input type="password" name="scraper_api_key"
                        value="{{ getSetting($settings, 'automation', 'scraper_api_key') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                        placeholder="Enter ScraperAPI Key (e.g., from scraperapi.com)">
                    <p class="text-xs text-gray-500 mt-1">Required if you want to automate Bajus scraping on a blocked
                        server.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Price Adjustment (%)</label>
                    <p class="text-xs text-gray-500 mb-2">Add a percentage markup to the raw API price (e.g., 5 for +5%).
                    </p>
                    <input type="number" step="0.01" name="gold_adjustment_percentage"
                        value="{{ getSetting($settings, 'automation', 'gold_adjustment_percentage') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300">
                </div>
            </div>

            <!-- Manual HTML Import Section -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h4 class="text-md font-bold text-gray-800 mb-4">Manual Import (Cloudflare Bypass)</h4>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <p class="text-sm text-orange-800 mb-4">
                        If automatic fetching fails (e.g., due to Cloudflare protection on Bajus.org), you can manually
                        paste the website's Source Code here.
                    </p>
                    <ol class="list-decimal list-inside text-xs text-gray-600 mb-4 space-y-1">
                        <li>Open <a href="https://bajus.org/gold-price" target="_blank"
                                class="text-blue-600 underline">Bajus Gold Price</a> in a new tab.</li>
                        <li>Right-click anywhere and select <strong>View Page Source</strong> (or Press Ctrl+U).</li>
                        <li>Select All (Ctrl+A), Copy (Ctrl+C), and Paste (Ctrl+V) into the box below.</li>
                    </ol>
                    <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')"
                        class="bg-orange-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-orange-700 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Import HTML Manually
                    </button>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <h4 class="text-md font-bold text-gray-800 mb-4">Cron Job Setup</h4>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm text-gray-700 mb-4">To automatically update prices, set up a cron job on your server
                        or
                        use a web-cron service.</p>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Server Command
                            (Recommended)</label>
                        <code class="block bg-gray-800 text-green-400 p-3 rounded font-mono text-xs select-all">
                                                        php artisan gold:fetch
                                                    </code>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Web URL (For Web-Cron)</label>
                        <div class="flex">
                            <input type="text" readonly value="{{ url('/api/cron/fetch-prices') }}"
                                class="flex-1 bg-gray-100 border border-gray-300 text-gray-600 text-sm rounded-l px-3 py-2 font-mono">
                            <a href="{{ url('/api/cron/fetch-prices') }}" target="_blank"
                                class="bg-blue-600 text-white px-4 py-2 rounded-r text-sm font-bold hover:bg-blue-700 flex items-center">
                                Test
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Configuration -->
        <div id="ai" class="tab-pane hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-4">AI Analysis Configuration</h3>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-purple-800">
                    <strong>Info:</strong> Configure Gemini AI to analyze gold ornaments and hallmark images.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gemini API Key</label>
                    <input type="password" name="gemini_api_key" value="{{ getSetting($settings, 'ai', 'gemini_api_key') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                        placeholder="Enter your Gemini API Key">
                    <p class="text-xs text-gray-500 mt-2">Get your key from <a href="https://aistudio.google.com/app/apikey"
                            target="_blank" class="text-blue-600 underline">Google AI Studio</a>.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gemini Model</label>
                    <input type="text" name="gemini_model" value="{{ getSetting($settings, 'ai', 'gemini_model') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 border-gray-300 font-mono text-sm"
                        placeholder="gemini-1.5-flash">
                    <p class="text-xs text-gray-500 mt-2">Specify the model version (e.g., gemini-1.5-flash,
                        gemini-2.0-flash-exp). Default: gemini-1.5-flash</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <button type="submit"
            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transform transition hover:scale-105">
            Save Settings
        </button>
    </div>
    </form>
    </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-xl leading-6 font-medium text-gray-900">Import Bajus Source Code</h3>
                    <button onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
                <div class="mt-2 text-sm text-gray-500">
                    <p class="mb-2">Paste the full HTML source code from bajus.org/gold-price below.</p>
                </div>
                <form action="{{ route('admin.settings.import') }}" method="POST">
                    @csrf
                    <div class="mt-2">
                        <textarea name="html_content" rows="15"
                            class="w-full p-2 border border-gray-300 rounded font-mono text-xs"
                            placeholder="<html>...</html>" required></textarea>
                    </div>
                    <div class="items-center px-4 py-3 mt-4 text-right">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded mr-2 hover:bg-gray-300">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold">Parse & Update
                            Prices</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            // Hide all tab content
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-pane");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.add("hidden");
                tabcontent[i].classList.remove("block");
            }

            // Deactivate all buttons
            tablinks = document.querySelectorAll("ul[role='tablist'] button");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("text-blue-600", "border-blue-600");
                tablinks[i].classList.add("border-transparent", "hover:text-gray-600", "hover:border-gray-300");
            }

            // Show current tab and activate button
            document.getElementById(tabName).classList.remove("hidden");
            document.getElementById(tabName).classList.add("block");

            evt.currentTarget.classList.remove("border-transparent", "hover:text-gray-600", "hover:border-gray-300");
            evt.currentTarget.classList.add("text-blue-600", "border-blue-600");
        }
    </script>
@endsection