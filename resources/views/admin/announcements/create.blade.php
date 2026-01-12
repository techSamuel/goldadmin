@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">New Announcement</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 border-gray-300">
                </div>

                <div class="mb-6">
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">Message Body</label>
                    <textarea name="body" id="body" rows="4" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 border-gray-300"></textarea>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Publish Immediately
                    </label>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.announcements.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create</button>
                </div>
            </form>
        </div>
    </div>
@endsection