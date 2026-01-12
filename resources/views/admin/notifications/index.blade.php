@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Push Notifications</h1>
                <p class="text-gray-600 mt-2">Manage and send push notifications to app users.</p>
            </div>
            <a href="{{ route('admin.notifications.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                + Send New Notification
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 text-sm uppercase tracking-wider border-b border-gray-200">
                        <th class="p-6 font-semibold">Image</th>
                        <th class="p-6 font-semibold">Title</th>
                        <th class="p-6 font-semibold">Body</th>
                        <th class="p-6 font-semibold">Type</th>
                        <th class="p-6 font-semibold">Sent At</th>
                        <th class="p-6 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($notifications as $notification)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-6">
                                @if($notification->image_url)
                                    <a href="{{ $notification->image_url }}" target="_blank">
                                        <img src="{{ $notification->image_url }}" alt="Img"
                                            class="w-10 h-10 rounded object-cover border">
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="p-6 font-medium text-gray-800">{{ $notification->title }}</td>
                            <td class="p-6 text-gray-600 max-w-xs truncate">{{ $notification->body }}</td>
                            <td class="p-6">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                                            @if($notification->type == 'promo') bg-green-100 text-green-700
                                                            @elseif($notification->type == 'alert') bg-red-100 text-red-700
                                                            @else bg-blue-100 text-blue-700 @endif">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </td>
                            <td class="p-6 text-gray-500 text-sm">{{ $notification->sent_at->format('M d, Y h:i A') }}</td>
                            <td class="p-6">
                                @if(isset($notification->response['success']) && $notification->response['success'])
                                    <span class="text-green-600 font-bold text-xs">Sent</span>
                                @else
                                    <span class="text-red-500 font-bold text-xs">Failed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-500">
                                No notifications found. Start by sending one!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection