<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GoldPrice;

$prices = GoldPrice::orderBy('id', 'desc')->take(2)->get(['id', 'karat_22', 'silver_price', 'created_at']);

foreach ($prices as $p) {
    echo "ID: {$p->id} | Gold 22K: {$p->karat_22} | Silver: {$p->silver_price} | Time: {$p->created_at}\n";
}
