<?php
echo "<h1>Permission Fixer</h1>";

$basePath = '/home/u374415227/domains/nawarika.shop/public_html/goldadmin';
$folders = [
    $basePath . '/storage',
    $basePath . '/storage/app',
    $basePath . '/storage/app/public',
    $basePath . '/storage/framework',
    $basePath . '/storage/framework/cache',
    $basePath . '/storage/framework/sessions',
    $basePath . '/storage/framework/views',
    $basePath . '/storage/logs',
    $basePath . '/bootstrap/cache',
];

echo "<ul>";
foreach ($folders as $folder) {
    if (is_dir($folder)) {
        if (chmod($folder, 0775)) {
            echo "<li style='color:green'>Fixed: $folder (775)</li>";
        } else {
            echo "<li style='color:red'>Failed: $folder</li>";
        }
    } else {
        echo "<li style='color:orange'>Skipped (Missing): $folder</li>";
    }
}
echo "</ul>";
echo "<p>Done! Try refreshing your website.</p>";
?>