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

// Fix Storage Link
Route::get('/fix-storage', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return "Storage Link Created Successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Debug Saved Proxy (Test DB Settings)
Route::get('debug-saved-proxy', function () {
    $url = 'https://bajus.org/gold-price';

    // Fetch from DB
    $proxy = \App\Models\Setting::where('key', 'socks5_proxy')->value('value');
    $user = \App\Models\Setting::where('key', 'socks5_user')->value('value');
    $pass = \App\Models\Setting::where('key', 'socks5_pass')->value('value');

    echo "<h1>SOCKS5 DB Debug</h1>";
    echo "<strong>Target:</strong> $url<br>";
    echo "<strong>Proxy from DB:</strong> " . ($proxy ?: 'Not Set') . "<br>";
    echo "<strong>User from DB:</strong> " . ($user ? 'Set' : 'Not Set') . "<br>";

    if (empty($proxy)) {
        echo "<h3 style='color:red'>Proxy Not Configured in Settings</h3>";
        return;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // SOCKS5 Config
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    if (!empty($user) && !empty($pass)) {
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$user:$pass");
    }
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);

    $startTime = microtime(true);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);
    curl_close($ch);
    $endTime = microtime(true);

    echo "<strong>Time:</strong> " . round($endTime - $startTime, 2) . "s<br>";
    echo "<strong>HTTP Code:</strong> " . $info['http_code'] . "<br>";

    if ($error) {
        echo "<h3 style='color:red'>cURL Error: $error</h3>";
    } elseif ($info['http_code'] == 200) {
        echo "<h3 style='color:green'>Success! DB Settings Work.</h3>";
        echo "<textarea style='width:100%; height:200px;'>" . htmlspecialchars(substr($output, 0, 2000)) . "</textarea>";
    } else {
        echo "<h3 style='color:orange'>Failed (Status " . $info['http_code'] . ")</h3>";
        echo "Likely blocked by Cloudflare.<br>";
        echo "<textarea style='width:100%; height:200px;'>" . htmlspecialchars(substr($output, 0, 2000)) . "</textarea>";
    }
});
