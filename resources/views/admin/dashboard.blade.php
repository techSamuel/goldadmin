@extends('layouts.admin')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-500">Overview of your app's performance</p>
        </div>
        <div class="flex gap-2">
            <span class="text-xs font-mono bg-gray-200 px-2 py-1 rounded">Last Setup: {{ now()->format('d M Y') }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- 22K Price -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Gold 22K (Today)</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">
                {{ $currentPrice ? number_format($currentPrice->karat_22) : '0' }} <span
                    class="text-sm font-normal text-gray-500">BDT</span>
            </div>
        </div>

        <!-- Notifications Sent -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Notifications Sent</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">
                {{ number_format($totalNotifications) }}
            </div>
        </div>

        <!-- Feedback Received -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Total Feedback</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">
                {{ number_format($totalFeedback) }}
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-teal-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Total Users</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">
                {{ number_format($totalUsers) }}
            </div>
        </div>

        <!-- Avg Rating -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Avg Rating</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 flex items-center">
                {{ number_format($averageRating, 1) }}
                <svg class="w-6 h-6 text-yellow-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Gold Price Trends (Last 30 Days)</h3>
            <canvas id="priceChart" height="150"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
            <div class="flex flex-col gap-4">
                <a href="{{ route('admin.prices.index') }}"
                    class="flex items-center justify-between px-4 py-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 border border-slate-200 transition">
                    <span class="font-medium">Update Prices Manually</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('admin.notifications.create') }}"
                    class="flex items-center justify-between px-4 py-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 border border-slate-200 transition">
                    <span class="font-medium">Send Push Notification</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center justify-between px-4 py-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 border border-slate-200 transition">
                    <span class="font-medium">Configure App</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('admin.feedback.index') }}"
                    class="flex items-center justify-between px-4 py-3 bg-slate-50 text-slate-700 rounded-lg hover:bg-slate-100 border border-slate-200 transition">
                    <span class="font-medium">View User Feedback</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('priceChart').getContext('2d');
        const priceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Gold 22K',
                        data: @json($data22k),
                        borderColor: '#EAB308', // Yellow-500
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Gold 21K',
                        data: @json($data21k),
                        borderColor: '#fbbf24', // Yellow-400
                        backgroundColor: 'rgba(251, 191, 36, 0.05)',
                        borderDash: [5, 5],
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>
@endsection