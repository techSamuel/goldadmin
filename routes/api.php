<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoldPriceController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\SettingController;

Route::get('/gold-prices', [GoldPriceController::class, 'index']);
Route::get('/ad-config', [\App\Http\Controllers\Admin\AdMobController::class, 'apiIndex']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/settings', [SettingController::class, 'index']);
Route::post('/feedback', [\App\Http\Controllers\Api\FeedbackController::class, 'store']);
Route::post('/register-device', [\App\Http\Controllers\Api\AppUserController::class, 'register']);

Route::get('/cron/fetch-prices', function () {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('gold:fetch');
    return response()->json([
        'message' => 'Gold price fetch command executed',
        'exit_code' => $exitCode,
        'output' => \Illuminate\Support\Facades\Artisan::output()
    ]);
});

Route::post('/analyze-gold', [\App\Http\Controllers\Api\AiAnalysisController::class, 'analyze']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
