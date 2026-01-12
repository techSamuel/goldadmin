<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SettingController;


// Redirect Root to Admin Login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/debug-fcm', function () {
    try {
        if (!class_exists('Google\Auth\Credentials\ServiceAccountCredentials')) {
            return 'Google Auth Library missing! Run "composer require google/auth"';
        }

        $fcm = new \App\Services\FCMService();
        $response = $fcm->sendNotification('Test Title', 'Test Body via Debug Route');
        return $response;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/privacy-policy', function () {
    $content = \App\Models\Setting::where('key', 'privacy_policy')->value('value');
    $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'App';

    return view('privacy-policy', compact('content', 'appName'));
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Config
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Auth Config
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Price Management
        Route::get('prices', [PriceController::class, 'index'])->name('prices.index');
        Route::post('prices', [PriceController::class, 'update'])->name('prices.update');
        Route::post('prices/fetch', [PriceController::class, 'fetch'])->name('prices.fetch');
        Route::get('prices/test', [PriceController::class, 'test'])->name('prices.test');

        // Announcements
        Route::resource('announcements', AnnouncementController::class);

        // Notifications
        Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class)->only(['index', 'create', 'store']);

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/import', [SettingController::class, 'importGoldHtml'])->name('settings.import');

        // Feedback
        Route::get('feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');

        // Users
        Route::resource('users', \App\Http\Controllers\Admin\AppUserController::class)->only(['index']);



        // Admin Management
        Route::resource('admins', \App\Http\Controllers\Admin\AdminManagementController::class);
    });
});

// Debug ScraperAPI
Route::get('debug-scraper', function () {
    $key = \App\Models\Setting::where('key', 'scraper_api_key')->value('value');
    if (!$key)
        return "No ScraperAPI Key found in settings.";

    $url = 'https://bajus.org/gold-price';

    $startTime = microtime(true);
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(90)->get('http://api.scraperapi.com', [
        'api_key' => $key,
        'url' => $url,
        'premium' => 'true', // Force premium proxies
    ]);
    $endTime = microtime(true);

    echo "<h1>ScraperAPI Debug</h1>";
    echo "<strong>Key:</strong> " . substr($key, 0, 5) . "...<br>";
    echo "<strong>Target:</strong> $url<br>";
    echo "<strong>Time:</strong> " . round($endTime - $startTime, 2) . "s<br>";
    echo "<strong>Status:</strong> " . $response->status() . "<br>";

    if ($response->failed()) {
        echo "<h3 style='color:red'>Request Failed</h3>";
        echo "<strong>Error Body:</strong> " . $response->body();
    } else {
        echo "<h3 style='color:green'>Request Successful</h3>";
        echo "<strong>Snippet:</strong> " . htmlspecialchars(substr($response->body(), 0, 1000));
    }
});

// Fix Storage Link
Route::get('/fix-storage', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return "Storage Link Created Successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
