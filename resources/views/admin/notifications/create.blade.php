@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.notifications.index') }}"
                class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to History</a>
            <h1 class="text-3xl font-bold text-gray-800">Send Notification</h1>
            <p class="text-gray-600 mt-2">Compose a new message to send to all users.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf

                <!-- Type -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Notification Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="type" value="general"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                            <span class="ml-2 text-gray-700">General</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="type" value="promo"
                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                            <span class="ml-2 text-gray-700">Promo</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="type" value="alert"
                                class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="ml-2 text-gray-700">Alert</span>
                        </label>
                    </div>
                </div>

                <!-- Image URL -->
                <div class="mb-6">
                    <label for="image_url" class="block text-sm font-bold text-gray-700 mb-2">Image URL (Optional)</label>
                    <input type="url" name="image_url" id="image_url"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                        placeholder="https://example.com/image.jpg">
                    <p class="text-xs text-gray-500 mt-1">Provide a direct link to an image (JPG/PNG) to display in the
                        notification.</p>
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                        placeholder="e.g. New Gold Prices!">
                </div>

                <!-- Body -->
                <div class="mb-6">
                    <label for="body" class="block text-sm font-bold text-gray-700 mb-2">Message Body</label>
                    <textarea name="body" id="body" rows="4" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                        placeholder="e.g. Check out the latest rates today..."></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection