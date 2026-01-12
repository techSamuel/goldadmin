@extends('layouts.admin')

@section('title', 'App Users')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">App Users</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6">Device Name</th>
                        <th class="py-3 px-6">Device ID</th>
                        <th class="py-3 px-6">OS Version</th>
                        <th class="py-3 px-6">IP Address</th>
                        <th class="py-3 px-6">Last Active</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($users as $user)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 font-bold">{{ $user->device_name }}</td>
                            <td class="py-3 px-6 font-mono text-xs">{{ $user->device_id }}</td>
                            <td class="py-3 px-6">{{ $user->os_version }}</td>
                            <td class="py-3 px-6">{{ $user->ip_address }}</td>
                            <td class="py-3 px-6">{{ $user->last_active_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection