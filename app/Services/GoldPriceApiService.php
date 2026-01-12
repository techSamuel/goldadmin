<?php

namespace App\Services;

use App\Models\GoldPrice;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoldPriceApiService
{
    protected $apiKey;
    protected $provider;
    protected $adjustment;

    public function __construct()
    {
        $this->apiKey = Setting::where('key', 'gold_api_key')->value('value');
        $this->provider = Setting::where('key', 'gold_api_provider')->value('value') ?? 'metalpriceapi';
        $this->adjustment = (float) Setting::where('key', 'gold_adjustment_percentage')->value('value') ?? 0;
    }

    public function fetchAndStorePrice()
    {
        try {
            // Check provider
            if ($this->provider === 'bajus') {
                return $this->fetchFromBajus();
            }

            if ($this->provider === 'metalpriceapi') {
                return $this->fetchFromMetalPriceApi();
            }

            return ['success' => false, 'message' => 'Unknown Provider'];

        } catch (\Exception $e) {
            Log::error('Gold Price Fetch Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function getMetalPriceData()
    {
        if (empty($this->apiKey))
            return null;

        try {
            $response = Http::get("https://api.metalpriceapi.com/v1/latest", [
                'api_key' => $this->apiKey,
                'base' => 'USD',
                'currencies' => 'XAU,XAG'
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('MetalPriceAPI (Hybrid) failed: ' . $e->getMessage());
        }
        return null;
    }

    public function updateFromHtml($html)
    {
        return $this->processBajusHtml($html);
    }

    private function fetchFromBajus()
    {
        $url = 'https://bajus.org/gold-price';
        // Fetch Settings
        $scraperKey = \App\Models\Setting::where('key', 'scraper_api_key')->value('value');
        $socksProxy = \App\Models\Setting::where('key', 'socks5_proxy')->value('value');
        $socksUser = \App\Models\Setting::where('key', 'socks5_user')->value('value');
        $socksPass = \App\Models\Setting::where('key', 'socks5_pass')->value('value');

        \Illuminate\Support\Facades\Log::info("GoldPriceApiService Settings: Proxy='" . ($socksProxy ? 'SET' : 'EMPTY') . "', User='" . ($socksUser ? 'SET' : 'EMPTY') . "'");

        if (!empty($socksProxy)) {
            \Illuminate\Support\Facades\Log::info("GoldPriceApiService: Starting SOCKS5 fetch to $url via $socksProxy");
            // Option 1: SOCKS5 Proxy (Raw cURL to match debug route success)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            // Proxy Config
            curl_setopt($ch, CURLOPT_PROXY, $socksProxy);
            if (!empty($socksUser) && !empty($socksPass)) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$socksUser:$socksPass");
            }
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME); // 7

            $body = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            \Illuminate\Support\Facades\Log::info("GoldPriceApiService: cURL Result - Code: $httpCode, Error: " . ($error ?: 'None') . ", Body Length: " . strlen($body));

            if ($error || $httpCode >= 400) {
                // Log error for debugging
                \Illuminate\Support\Facades\Log::error("SOCKS5 Fail: $error | Code: $httpCode");
                throw new \Exception("Failed to connect via Proxy. Error: $error, Code: $httpCode");
            }

            // Success - mimic Guzzle response structure if needed, or just return body
            if (empty($body)) {
                throw new \Exception("Empty response from Proxy");
            }
            return $this->processBajusHtml($body);

        } elseif (!empty($scraperKey)) {
            // Option 2: ScraperAPI
            $response = Http::withoutVerifying()
                ->timeout(90)
                ->get('http://api.scraperapi.com', [
                    'api_key' => $scraperKey,
                    'url' => $url,
                    'keep_headers' => 'true',
                    'premium' => 'true',
                ]);
        } else {
            // Option 3: Direct Connection (Fallback)
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ])
                ->get($url);
        }

        if ($response->failed()) {
            throw new \Exception('Failed to connect to BAJUS website');
        }

        return $this->processBajusHtml($response->body());
    }

    private function processBajusHtml($html)
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        $getPrice = function ($label) use ($xpath) {
            $nodes = $xpath->query("//tr[contains(., '$label')]//span[contains(@class, 'price')]");
            if ($nodes->length > 0) {
                // Extract numbers
                return (float) preg_replace('/[^0-9.]/', '', $nodes->item(0)->textContent);
            }
            return 0;
        };

        // Extract BDT Prices
        $gold22 = $getPrice('22 KARAT Gold');
        $gold21 = $getPrice('21 KARAT Gold');
        $gold18 = $getPrice('18 KARAT Gold');
        $goldTraditional = $getPrice('TRADITIONAL Gold');

        $silver22 = $getPrice('22 KARAT Silver');
        // $silver21 = $getPrice('21 KARAT Silver'); // Optional
        $silverTraditional = $getPrice('TRADITIONAL Silver');

        if ($gold22 == 0) {
            Log::error('Bajus Scrape Failed. HTML snippet: ' . substr($html, 0, 500));
            throw new \Exception('Failed to scrape prices from BAJUS');
        }

        // 2. Fetch USD from MetalPriceAPI (Hybrid) - Optional
        $usdPrices = [
            'karat_24_usd' => 0,
            'karat_22_usd' => 0,
            'karat_21_usd' => 0,
            'karat_18_usd' => 0,
            'traditional_gold_usd' => 0,
            'silver_price_usd' => 0,
            'silver_24_usd' => 0,
            'silver_21_usd' => 0,
            'silver_18_usd' => 0,
            'traditional_silver_usd' => 0
        ];

        // Attempt fetching USD if API key is present
        $metalData = $this->getMetalPriceData();

        if ($metalData && isset($metalData['rates']['XAU'])) {
            $xau_usd_oz = $metalData['rates']['XAU'];
            $xag_usd_oz = $metalData['rates']['XAG'] ?? 0;

            $gold_gram_usd_24k = $xau_usd_oz / 31.1034768;
            $silver_gram_usd = $xag_usd_oz / 31.1034768;

            // Apply Markup to USD as well
            $markupUSD = 1 + ($this->adjustment / 100);
            $gold_gram_usd_24k *= $markupUSD;
            $silver_gram_usd *= $markupUSD;

            $usdPrices = [
                'karat_24_usd' => $gold_gram_usd_24k,
                'karat_22_usd' => $gold_gram_usd_24k * 0.9167,
                'karat_21_usd' => $gold_gram_usd_24k * 0.875,
                'karat_18_usd' => $gold_gram_usd_24k * 0.75,
                'traditional_gold_usd' => $gold_gram_usd_24k * 0.80,

                'silver_price_usd' => $silver_gram_usd,
                'silver_24_usd' => $silver_gram_usd,
                'silver_21_usd' => $silver_gram_usd * 0.875,
                'silver_18_usd' => $silver_gram_usd * 0.75,
                'traditional_silver_usd' => $silver_gram_usd * 0.80,
            ];
        }

        // Apply Adjustment Percentage to BDT
        $markup = 1 + ($this->adjustment / 100);

        // Merge BDT (Bajus) + USD (MetalPrice)
        $prices = array_merge($usdPrices, [
            'karat_24' => ($gold22 * 1.05) * $markup, // Estimate
            'karat_22' => $gold22 * $markup,
            'karat_21' => $gold21 * $markup,
            'karat_18' => $gold18 * $markup,
            'traditional_gold' => $goldTraditional * $markup,

            'silver_price' => $silver22 * $markup,
            'silver_24' => ($silver22 * 1.05) * $markup,
            'silver_21' => ($silver22 * 0.95) * $markup, // Appx
            'silver_18' => ($silver22 * 0.82) * $markup, // Appx
            'traditional_silver' => $silverTraditional * $markup,
        ]);

        GoldPrice::create($prices);

        return ['success' => true, 'data' => $prices];
    }

    private function fetchFromMetalPriceApi()
    {
        if (empty($this->apiKey)) {
            Log::error('Gold API Key is missing.');
            return ['success' => false, 'message' => 'API Key missing'];
        }

        $metalData = $this->getMetalPriceData();
        if (!$metalData)
            return ['success' => false, 'message' => 'API Request Failed'];

        $xau_usd_oz = $metalData['rates']['XAU'] ?? 0;
        $xag_usd_oz = $metalData['rates']['XAG'] ?? 0;

        if ($xau_usd_oz == 0)
            throw new \Exception('Invalid Gold Data');

        // Fallback or Fixed BDT rate if using MetalPriceAPI
        $usd_to_bdt = 120;

        $gold_gram_usd_24k = $xau_usd_oz / 31.1034768;
        $silver_gram_usd = $xag_usd_oz / 31.1034768;

        $markup = 1 + ($this->adjustment / 100);
        $gold_gram_usd_24k *= $markup;
        $silver_gram_usd *= $markup;

        $prices = [
            'karat_24_usd' => $gold_gram_usd_24k,
            'karat_22_usd' => $gold_gram_usd_24k * 0.9167,
            'karat_21_usd' => $gold_gram_usd_24k * 0.875,
            'karat_18_usd' => $gold_gram_usd_24k * 0.75,

            'karat_24' => $gold_gram_usd_24k * $usd_to_bdt,
            'karat_22' => ($gold_gram_usd_24k * 0.9167) * $usd_to_bdt,
            'karat_21' => ($gold_gram_usd_24k * 0.875) * $usd_to_bdt,
            'karat_18' => ($gold_gram_usd_24k * 0.75) * $usd_to_bdt,

            'traditional_gold' => ($gold_gram_usd_24k * 0.80) * $usd_to_bdt,
            'traditional_gold_usd' => $gold_gram_usd_24k * 0.80,

            'silver_price' => $silver_gram_usd * $usd_to_bdt,
            'silver_price_usd' => $silver_gram_usd,
            'silver_24' => $silver_gram_usd * $usd_to_bdt,
            'silver_24_usd' => $silver_gram_usd,
            'silver_21' => ($silver_gram_usd * 0.875) * $usd_to_bdt,
            'silver_21_usd' => $silver_gram_usd * 0.875,
            'silver_18' => ($silver_gram_usd * 0.75) * $usd_to_bdt,
            'silver_18_usd' => $silver_gram_usd * 0.75,
            'traditional_silver' => ($silver_gram_usd * 0.80) * $usd_to_bdt,
            'traditional_silver_usd' => $silver_gram_usd * 0.80,
        ];

        GoldPrice::create($prices);

        return ['success' => true, 'data' => $prices];
    }
}
