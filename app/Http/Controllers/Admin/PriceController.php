<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $currentPrice = \App\Models\GoldPrice::latest()->first();
        return view('admin.prices.index', compact('currentPrice'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'karat_24' => 'required|numeric|min:0',
            'karat_24_usd' => 'nullable|numeric|min:0',
            'karat_22' => 'required|numeric|min:0',
            'karat_22_usd' => 'nullable|numeric|min:0',
            'karat_21' => 'required|numeric|min:0',
            'karat_21_usd' => 'nullable|numeric|min:0',
            'karat_18' => 'required|numeric|min:0',
            'karat_18_usd' => 'nullable|numeric|min:0',
            'traditional_gold' => 'required|numeric|min:0',
            'traditional_gold_usd' => 'nullable|numeric|min:0',
            'silver_24' => 'required|numeric|min:0',
            'silver_24_usd' => 'nullable|numeric|min:0',
            'silver_price' => 'required|numeric|min:0',
            'silver_price_usd' => 'nullable|numeric|min:0',
            'silver_21' => 'required|numeric|min:0',
            'silver_21_usd' => 'nullable|numeric|min:0',
            'silver_18' => 'required|numeric|min:0',
            'silver_18_usd' => 'nullable|numeric|min:0',
            'traditional_silver' => 'required|numeric|min:0',
            'traditional_silver_usd' => 'nullable|numeric|min:0',
        ]);

        \App\Models\GoldPrice::create($validated);

        return back()->with('success', 'Prices updated successfully!');
    }

    public function test(\App\Services\BajusScraperService $scraper)
    {
        try {
            $prices = $scraper->fetchPrices();
            return response()->json([
                'status' => 'success',
                'data' => $prices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function fetch(\App\Services\BajusScraperService $scraper)
    {
        try {
            $prices = $scraper->fetchPrices();
            \Illuminate\Support\Facades\Log::info('Fetched Prices from Bajus:', $prices);

            \App\Models\GoldPrice::create($prices);

            return back()->with('success', 'Prices fetched from Bajus.org and updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Scraping failed: ' . $e->getMessage());
        }
    }
}
