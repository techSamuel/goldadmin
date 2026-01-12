<?php
$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
    "http" => [
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
    ]
]);

$html = file_get_contents('https://bajus.org/gold-price', false, $context);

if ($html === false) {
    echo "Failed to fetch URL\n";
    exit(1);
}

file_put_contents('bajus_content.html', $html);
echo "Content saved to bajus_content.html\n";
