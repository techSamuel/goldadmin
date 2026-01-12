<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Calculator Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @auth('admin')
            <div class="w-64 bg-slate-900 text-white flex-shrink-0">
                <div class="p-6">
                    <div class="flex items-center gap-2">
                        @if(isset($branding['admin_logo']) && $branding['admin_logo'])
                            <img src="{{ $branding['admin_logo'] }}" alt="Logo" class="h-8 w-auto">
                        @endif
                        <h1 class="text-xl font-bold text-yellow-500">{{ $branding['admin_panel_name'] ?? 'Gold Admin' }}
                        </h1>
                    </div>
                </div>
                <nav class="mt-4">
                    <a href="{{ route('admin.dashboard') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.prices.index') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.prices.*') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">Manage
                        Prices</a>
                    <a href="{{ route('admin.announcements.index') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.announcements.*') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">Announcements</a>
                    <a href="{{ route('admin.notifications.index') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.notifications.*') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">Notifications</a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">Settings</a>
                    <a href="{{ route('admin.feedback.index') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.feedback.*') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">Feedback</a>
                    <a href="{{ route('admin.users.index') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">App
                        Users</a>


                    <a href="{{ route('admin.admins.index') }}"
                        class="block py-3 px-6 hover:bg-slate-800 {{ request()->routeIs('admin.admins.*') ? 'bg-slate-800 border-l-4 border-yellow-500' : '' }}">Manage
                        Admins</a>

                </nav>
                <div class="absolute bottom-0 w-64 p-4">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded">Logout</button>
                    </form>
                </div>
            </div>
        @endauth

        <!-- Content -->
        <div class="flex-1 flex flex-col">
            <main class="flex-1 p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('notification_status'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">🔔 {{ session('notification_status') }}</span>
                    </div>
                @endif

                @if(session('notification_error'))
                    <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">⚠️ {{ session('notification_error') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>