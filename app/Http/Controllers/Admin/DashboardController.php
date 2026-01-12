<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentPrice = \App\Models\GoldPrice::latest()->first();

        // Stats
        $totalNotifications = \App\Models\Notification::count();
        $totalFeedback = \App\Models\Feedback::count();
        $totalUsers = \App\Models\AppUser::count(); // Add User Count
        $averageRating = \App\Models\Feedback::avg('rating') ?? 0;

        // Chart Data (Last 30 Days)
        $history = \App\Models\GoldPrice::select('created_at', 'karat_22', 'karat_21')
            ->orderBy('created_at', 'asc')
            ->limit(30) // In a real app, you might valid by date range
            ->get();

        $labels = $history->pluck('created_at')->map(fn($date) => $date->format('M d'))->toArray();
        $data22k = $history->pluck('karat_22')->toArray();
        $data21k = $history->pluck('karat_21')->toArray();

        return view('admin.dashboard', compact(
            'currentPrice',
            'totalNotifications',
            'totalFeedback',
            'totalUsers',
            'averageRating',
            'labels',
            'data22k',
            'data21k'
        ));
    }
}
