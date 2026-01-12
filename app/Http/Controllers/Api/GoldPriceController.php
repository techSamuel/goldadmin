<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoldPriceController extends Controller
{
    public function index()
    {
        $prices = \App\Models\GoldPrice::latest()->take(2)->get();

        $current = $prices->first();
        $previous = $prices->count() > 1 ? $prices->skip(1)->first() : null;

        return response()->json([
            'success' => true,
            'data' => [
                'current' => $current,
                'previous' => $previous
            ]
        ]);
    }
}
