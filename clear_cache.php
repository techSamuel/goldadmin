<?php
echo "<h1>Cache Cleaner</h1>";

$files = [
    __DIR__ . '/bootstrap/cache/config.php',
    __DIR__ . '/bootstrap/cache/routes.php',
    __DIR__ . '/bootstrap/cache/packages.php',
    __DIR__ . '/bootstrap/cache/services.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "<p style='color:green'>Deleted: " . basename($file) . "</p>";
        } else {
            echo "<p style='color:red'>Failed to delete: " . basename($file) . " (Check permissions)</p>";
        }
    } else {
        echo "<p style='color:gray'>Not found: " . basename($file) . " (Already clear)</p>";
    }
}

echo "<p>Caches cleared. Try refreshing your site.</p>";
?>