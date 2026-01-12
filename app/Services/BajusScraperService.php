<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class BajusScraperService
{
    protected $url = 'https://bajus.org/gold-price';

    public function fetchPrices()
    {
        try {
            // Fetch content
            $response = Http::withoutVerifying()->get($this->url);

            if (!$response->successful()) {
                throw new Exception('Failed to connect to Bajus.org');
            }

            $html = $response->body();

            // Regex for <span class="price">18,700 BDT/GRAM</span>
            // Matches numbers with commas
            $pattern = '/<span class="price">([\d,]+)\s*BDT\/GRAM<\/span>/';

            preg_match_all($pattern, $html, $matches);

            if (empty($matches[1]) || count($matches[1]) < 8) {
                // Try alternate pattern if spacing varies
                $pattern = '/class="price"\s*>([\d,]+)\s*BDT\/GRAM/';
                preg_match_all($pattern, $html, $matches);

                if (empty($matches[1])) {
                    throw new Exception('Could not find price pattern in HTML');
                }
            }

            $prices = $matches[1];

            // Clean numbers (remove commas)
            $cleaned = array_map(function ($p) {
                return (float) str_replace(',', '', $p);
            }, $prices);

            // Mapping based on visual order in HTML:
            // 0: 22K Gold
            // 1: 21K Gold
            // 2: 18K Gold
            // 3: Traditional Gold
            // 4: 22K Silver
            // 5: 21K Silver
            // 6: 18K Silver
            // 7: Traditional Silver

            $gold22 = $cleaned[0] ?? 0;
            $gold21 = $cleaned[1] ?? 0;
            $gold18 = $cleaned[2] ?? 0;
            $goldTraditional = $cleaned[3] ?? 0;

            $silver22 = $cleaned[4] ?? 0;
            $silver21 = $cleaned[5] ?? 0;
            $silver18 = $cleaned[6] ?? 0;
            $silverTraditional = $cleaned[7] ?? 0;

            // Calculate implied 24K (Base)
            // 22K is approx 91.6% pure. 
            // 24K = 22K * (24/22)
            $gold24 = $gold22 * (24 / 22);
            $silver24 = $silver22 * (24 / 22);

            return [
                'karat_24' => round($gold24, 2),
                'karat_22' => $gold22,
                'karat_21' => $gold21,
                'karat_18' => $gold18,
                'traditional_gold' => $goldTraditional,
                'silver_24' => round($silver24, 2),
                'silver_price' => $silver22, // Maps to 22K
                'silver_21' => $silver21,
                'silver_18' => $silver18,
                'traditional_silver' => $silverTraditional,
            ];

        } catch (Exception $e) {
            throw $e;
        }
    }
}
