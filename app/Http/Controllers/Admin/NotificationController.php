<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Services\FCMService;

class NotificationController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function index()
    {
        $notifications = Notification::latest()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:general,promo,alert',
            'image_url' => 'nullable|url',
        ]);

        // Send via FCM
        $result = $this->fcmService->sendNotification(
            $request->title,
            $request->body,
            'general', // Topic
            $request->image_url
        );

        // Store in DB
        Notification::create([
            'title' => $request->title,
            'body' => $request->body,
            'type' => $request->type,
            'image_url' => $request->image_url,
            'response' => $result,
            'sent_at' => now(),
        ]);

        if ($result['success']) {
            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notification sent successfully!');
        } else {
            return redirect()->route('admin.notifications.index')
                ->with('error', 'Notification sent to FCM but failed. Check logs/response.');
        }
    }
}
