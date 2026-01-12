@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">User Feedback</h1>
                <p class="text-gray-600 mt-2">View ratings and messages from your users.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Message</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($feedbacks as $feedback)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $feedback->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $feedback->name ?? 'Anonymous' }}</div>
                                <div class="text-xs text-gray-500">{{ $feedback->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-md">
                                {{ $feedback->message }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No feedback received yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($feedbacks->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection