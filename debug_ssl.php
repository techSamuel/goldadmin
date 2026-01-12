<?php
echo "<h1>SSL / Header Debugger</h1>";

$headers = [
    'HTTPS',
    'HTTP_X_FORWARDED_PROTO',
    'HTTP_X_FORWARDED_SSL',
    'HTTP_CLOUDFRONT_FORWARDED_PROTO',
    'SERVER_PORT',
    'REQUEST_SCHEME'
];

echo "<table border='1' cellpadding='5'>";
foreach ($headers as $h) {
    echo "<tr><td>$h</td><td>" . ($_SERVER[$h] ?? '<span style="color:red">MISSING</span>') . "</td></tr>";
}
echo "</table>";

echo "<h2>Full Server Dump</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
?>