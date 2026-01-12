<?php
// Absolute Path from User
$basePath = '/home/u374415227/domains/nawarika.shop/public_html/goldadmin';
$targetStore = $basePath . '/storage/app/public';

echo "<h1>Symlink Diagnostic & Setup</h1>";
echo "<p>Base Path: $basePath</p>";

// Check if Target Exists
if (!is_dir($targetStore)) {
    echo "<p style='color:red'>CRITICAL: Target storage folder not found at: $targetStore</p>";
    echo "<p>Please ensure you uploaded the 'storage' folder inside 'goldadmin'.</p>";
    die();
}
echo "<p style='color:green'>&#10004; Found Target Storage: $targetStore</p>";

// Determine where to put the link
// Preference 1: inside 'public' folder (Standard Laravel)
$publicFolder = $basePath . '/public';
$linkPath = '';

if (is_dir($publicFolder)) {
    $linkPath = $publicFolder . '/storage';
    echo "<p>Found 'public' folder. Creating link at: $linkPath</p>";
} else {
    // Preference 2: Root (if user merged public into root - RISKY due to collision)
    $linkPath = $basePath . '/storage';
    echo "<p>No 'public' folder found. Checking root...</p>";

    if (is_dir($linkPath) && !is_link($linkPath)) {
        echo "<p style='color:red'>COLLISION: A real folder named 'storage' already exists in root.</p>";
        echo "<p><strong>Solution:</strong> You likely moved the contents of 'public' to the root folder. <br>Laravel cannot work this way because 'public/storage' (link) and 'app/storage' (folder) conflict.<br>";
        echo "Please move your public files back into a 'public' folder and point your domain there.</p>";
        die();
    }
}

// Attempt Creation
if (file_exists($linkPath)) {
    echo "<p style='color:orange'>Link/Folder already exists at: $linkPath</p>";
    if (is_link($linkPath)) {
        echo "<p>It is already a symbolic link. You are good to go!</p>";
    } else {
        echo "<p>It is a directory, not a link. Delete it and run this script again.</p>";
    }
} else {
    if (symlink($targetStore, $linkPath)) {
        echo "<p style='color:green; font-size: 20px;'>&#10004; SUCCESS: Symlink Created!</p>";
        echo "<p>Target: $targetStore<br>Link: $linkPath</p>";
    } else {
        echo "<p style='color:red'>FAILED: Could not create symlink. Check permissions.</p>";
    }
}
?>