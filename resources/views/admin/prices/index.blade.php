@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Manage Prices</h1>
            <p class="text-gray-600 mt-2">Update the current market price for Gold and Silver. History is preserved.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            <form action="{{ route('admin.prices.update') }}" method="POST">
                @csrf

                <!-- Tabs -->
                <div class="mb-6 border-b border-gray-200">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                        data-tabs-toggle="#myTabContent" role="tablist">
                        <li class="mr-2" role="presentation">
                            <button
                                class="inline-block p-4 rounded-t-lg border-b-2 text-yellow-600 border-yellow-600 active"
                                id="bdt-tab" data-tabs-target="#bdt" type="button" role="tab" aria-controls="bdt"
                                aria-selected="true" onclick="switchTab('bdt')">BDT Prices</button>
                        </li>
                        <li class="mr-2" role="presentation">
                            <button
                                class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                                id="usd-tab" data-tabs-target="#usd" type="button" role="tab" aria-controls="usd"
                                aria-selected="false" onclick="switchTab('usd')">USD Prices</button>
                        </li>
                    </ul>
                </div>

                <div id="myTabContent">
                    <!-- BDT Section -->
                    <div id="bdt" role="tabpanel" aria-labelledby="bdt-tab">
                        <!-- Gold Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-yellow-600 mb-4 border-b pb-2">Gold Prices (Per Gram) -
                                BDT</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- 24K Base Price -->
                                <div class="col-span-1 md:col-span-2 bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                                    <label for="karat_24" class="block text-sm font-bold text-gray-800 mb-2">24 Karat (Base
                                        Price)</label>
                                    <div class="flex items-center">
                                        <input type="number" step="0.01" name="karat_24" id="karat_24"
                                            value="{{ old('karat_24', $currentPrice->karat_24 ?? '') }}"
                                            class="w-full px-4 py-3 text-xl font-bold border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 border-gray-300"
                                            placeholder="Enter 24K Price">
                                        <button type="button" onclick="calculatePrices()"
                                            class="ml-4 px-6 py-3 bg-slate-800 text-white font-medium rounded-lg hover:bg-slate-700 transition">
                                            Auto Calculate
                                        </button>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" form="fetch-prices-form"
                                            class="text-sm font-semibold text-teal-700 hover:text-teal-900 flex items-center">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                </path>
                                            </svg>
                                            Fetch Live Rates from Bajus.org
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">✨ Enter 24K price and click "Auto Calculate" to
                                        generate other values.</p>
                                </div>

                                <!-- 22K -->
                                <div>
                                    <label for="karat_22" class="block text-sm font-medium text-gray-700 mb-2">22
                                        Karat</label>
                                    <input type="number" step="0.01" name="karat_22" id="karat_22"
                                        value="{{ old('karat_22', $currentPrice->karat_22 ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 border-gray-300">
                                </div>

                                <!-- 21K -->
                                <div>
                                    <label for="karat_21" class="block text-sm font-medium text-gray-700 mb-2">21
                                        Karat</label>
                                    <input type="number" step="0.01" name="karat_21" id="karat_21"
                                        value="{{ old('karat_21', $currentPrice->karat_21 ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 border-gray-300">
                                </div>

                                <!-- 18K -->
                                <div>
                                    <label for="karat_18" class="block text-sm font-medium text-gray-700 mb-2">18
                                        Karat</label>
                                    <input type="number" step="0.01" name="karat_18" id="karat_18"
                                        value="{{ old('karat_18', $currentPrice->karat_18 ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 border-gray-300">
                                </div>

                                <!-- Traditional Gold -->
                                <div>
                                    <label for="traditional_gold"
                                        class="block text-sm font-medium text-gray-700 mb-2">Traditional</label>
                                    <input type="number" step="0.01" name="traditional_gold" id="traditional_gold"
                                        value="{{ old('traditional_gold', $currentPrice->traditional_gold ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-yellow-500 focus:border-yellow-500 border-gray-300">
                                </div>
                            </div>
                        </div>

                        <!-- Silver Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-600 mb-4 border-b pb-2">Silver Prices (Per Gram) -
                                BDT</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- 24K Silver Base Price -->
                                <div class="col-span-1 md:col-span-2 bg-gray-50 p-6 rounded-xl border border-gray-200">
                                    <label for="silver_24" class="block text-sm font-bold text-gray-800 mb-2">24 Karat (Base
                                        Price)</label>
                                    <div class="flex items-center">
                                        <input type="number" step="0.01" name="silver_24" id="silver_24"
                                            value="{{ old('silver_24', $currentPrice->silver_24 ?? '') }}"
                                            class="w-full px-4 py-3 text-xl font-bold border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300"
                                            placeholder="Enter 24K Silver Price">
                                        <button type="button" onclick="calculateSilverPrices()"
                                            class="ml-4 px-6 py-3 bg-slate-800 text-white font-medium rounded-lg hover:bg-slate-700 transition">
                                            Auto Calculate
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">✨ Enter 24K price and click "Auto Calculate" to
                                        generate other values.</p>
                                </div>

                                <!-- 22K Silver (Mapped to silver_price) -->
                                <div>
                                    <label for="silver_price" class="block text-sm font-medium text-gray-700 mb-2">22
                                        Karat</label>
                                    <input type="number" step="0.01" name="silver_price" id="silver_price"
                                        value="{{ old('silver_price', $currentPrice->silver_price ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>

                                <!-- 21K Silver -->
                                <div>
                                    <label for="silver_21" class="block text-sm font-medium text-gray-700 mb-2">21
                                        Karat</label>
                                    <input type="number" step="0.01" name="silver_21" id="silver_21"
                                        value="{{ old('silver_21', $currentPrice->silver_21 ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>

                                <!-- 18K Silver -->
                                <div>
                                    <label for="silver_18" class="block text-sm font-medium text-gray-700 mb-2">18
                                        Karat</label>
                                    <input type="number" step="0.01" name="silver_18" id="silver_18"
                                        value="{{ old('silver_18', $currentPrice->silver_18 ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>

                                <!-- Traditional Silver -->
                                <div>
                                    <label for="traditional_silver"
                                        class="block text-sm font-medium text-gray-700 mb-2">Traditional</label>
                                    <input type="number" step="0.01" name="traditional_silver" id="traditional_silver"
                                        value="{{ old('traditional_silver', $currentPrice->traditional_silver ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- USD Section -->
                    <div id="usd" class="hidden" role="tabpanel" aria-labelledby="usd-tab">
                        <!-- Gold Section USD -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-green-600 mb-4 border-b pb-2">Gold Prices (Per Gram) - USD
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="karat_24_usd" class="block text-sm font-bold text-gray-800 mb-2">24
                                        Karat</label>
                                    <input type="number" step="0.01" name="karat_24_usd" id="karat_24_usd"
                                        value="{{ old('karat_24_usd', $currentPrice->karat_24_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="karat_22_usd" class="block text-sm font-medium text-gray-700 mb-2">22
                                        Karat</label>
                                    <input type="number" step="0.01" name="karat_22_usd" id="karat_22_usd"
                                        value="{{ old('karat_22_usd', $currentPrice->karat_22_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="karat_21_usd" class="block text-sm font-medium text-gray-700 mb-2">21
                                        Karat</label>
                                    <input type="number" step="0.01" name="karat_21_usd" id="karat_21_usd"
                                        value="{{ old('karat_21_usd', $currentPrice->karat_21_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="karat_18_usd" class="block text-sm font-medium text-gray-700 mb-2">18
                                        Karat</label>
                                    <input type="number" step="0.01" name="karat_18_usd" id="karat_18_usd"
                                        value="{{ old('karat_18_usd', $currentPrice->karat_18_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="traditional_gold_usd"
                                        class="block text-sm font-medium text-gray-700 mb-2">Traditional</label>
                                    <input type="number" step="0.01" name="traditional_gold_usd" id="traditional_gold_usd"
                                        value="{{ old('traditional_gold_usd', $currentPrice->traditional_gold_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 border-gray-300">
                                </div>
                            </div>
                        </div>

                        <!-- Silver Section USD -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-600 mb-4 border-b pb-2">Silver Prices (Per Gram) -
                                USD</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="silver_24_usd" class="block text-sm font-bold text-gray-800 mb-2">24
                                        Karat</label>
                                    <input type="number" step="0.01" name="silver_24_usd" id="silver_24_usd"
                                        value="{{ old('silver_24_usd', $currentPrice->silver_24_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="silver_price_usd" class="block text-sm font-medium text-gray-700 mb-2">22
                                        Karat</label>
                                    <input type="number" step="0.01" name="silver_price_usd" id="silver_price_usd"
                                        value="{{ old('silver_price_usd', $currentPrice->silver_price_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="silver_21_usd" class="block text-sm font-medium text-gray-700 mb-2">21
                                        Karat</label>
                                    <input type="number" step="0.01" name="silver_21_usd" id="silver_21_usd"
                                        value="{{ old('silver_21_usd', $currentPrice->silver_21_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="silver_18_usd" class="block text-sm font-medium text-gray-700 mb-2">18
                                        Karat</label>
                                    <input type="number" step="0.01" name="silver_18_usd" id="silver_18_usd"
                                        value="{{ old('silver_18_usd', $currentPrice->silver_18_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>
                                <div>
                                    <label for="traditional_silver_usd"
                                        class="block text-sm font-medium text-gray-700 mb-2">Traditional</label>
                                    <input type="number" step="0.01" name="traditional_silver_usd"
                                        id="traditional_silver_usd"
                                        value="{{ old('traditional_silver_usd', $currentPrice->traditional_silver_usd ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-gray-500 focus:border-gray-500 border-gray-300">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-8 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg shadow-lg transform transition hover:scale-105">
                        Update All Prices
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all tab content
            document.getElementById('bdt').classList.add('hidden');
            document.getElementById('usd').classList.add('hidden');

            // Remove active style from tabs
            document.getElementById('bdt-tab').classList.remove('text-yellow-600', 'border-yellow-600');
            document.getElementById('bdt-tab').classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');

            document.getElementById('usd-tab').classList.remove('text-yellow-600', 'border-yellow-600');
            document.getElementById('usd-tab').classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');

            // Show selected tab
            document.getElementById(tabId).classList.remove('hidden');

            // Add active style to selected tab
            document.getElementById(tabId + '-tab').classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
            document.getElementById(tabId + '-tab').classList.add('text-yellow-600', 'border-yellow-600');
        }

        function calculateSilverPrices() {
            const s24 = parseFloat(document.getElementById('silver_24').value) || 0;

            if (s24 > 0) {
                // 22K = 91.6% of 24K
                document.getElementById('silver_price').value = (s24 * (22 / 24)).toFixed(2);
                document.getElementById('silver_21').value = (s24 * (21 / 24)).toFixed(2);
                document.getElementById('silver_18').value = (s24 * (18 / 24)).toFixed(2);
            } else {
                alert('Please enter a valid 24K Silver price first');
            }
        }

        function calculatePrices() {
            const k24 = parseFloat(document.getElementById('karat_24').value) || 0;

            // Standard calculation ratios (typical logic, can be adjusted)
            // 22K = 91.6% of 24K
            // 21K = 87.5% of 24K
            // 18K = 75.0% of 24K

            if (k24 > 0) {
                document.getElementById('karat_22').value = (k24 * (22 / 24)).toFixed(2);
                document.getElementById('karat_21').value = (k24 * (21 / 24)).toFixed(2);
                document.getElementById('karat_18').value = (k24 * (18 / 24)).toFixed(2);
            } else {
                alert('Please enter a valid 24K price first');
            }
        }
    </script>
    <form id="fetch-prices-form" action="{{ route('admin.prices.fetch') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection