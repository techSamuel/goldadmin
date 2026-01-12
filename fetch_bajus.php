<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$url = 'https://bajus.org/gold-price';
$html = file_get_contents($url);

file_put_contents('bajus.html', $html);

echo "Fetched " . strlen($html) . " bytes.\n";
