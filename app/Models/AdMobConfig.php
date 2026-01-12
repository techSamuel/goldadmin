<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdMobConfig extends Model
{
    protected $fillable = [
        'platform',
        'banner_id',
        'interstitial_id',
        'rewarded_id',
        'is_enabled',
    ];
}
