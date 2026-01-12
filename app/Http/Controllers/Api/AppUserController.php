<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'device_name' => 'nullable|string',
            'os_version' => 'nullable|string',
        ]);

        $user = \App\Models\AppUser::updateOrCreate(
            ['device_id' => $request->device_id],
            [
                'device_name' => $request->device_name,
                'os_version' => $request->os_version,
                'ip_address' => $request->ip(),
                'last_active_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
