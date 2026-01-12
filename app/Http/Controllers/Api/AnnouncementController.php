<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = \App\Models\Announcement::where('is_active', true)
            ->latest()
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $announcements
        ]);
    }
}
