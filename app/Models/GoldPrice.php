<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'karat_24',
        'karat_24_usd',
        'karat_22',
        'karat_22_usd',
        'karat_21',
        'karat_21_usd',
        'karat_18',
        'karat_18_usd',
        'traditional_gold',
        'traditional_gold_usd',
        'silver_24',
        'silver_24_usd',
        'silver_price', // This maps to Silver 22K
        'silver_price_usd',
        'silver_21',
        'silver_21_usd',
        'silver_18',
        'silver_18_usd',
        'traditional_silver',
        'traditional_silver_usd',
    ];

    //
}
