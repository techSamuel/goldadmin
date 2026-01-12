<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - {{ $appName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
        }

        .prose {
            max-width: 65ch;
            margin: 0 auto;
            line-height: 1.6;
        }

        .prose h1 {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 0.5em;
        }

        .prose h2 {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
        }

        .prose p {
            margin-bottom: 1em;
        }

        .prose ul {
            list-style-type: disc;
            margin-left: 1.5em;
            margin-bottom: 1em;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 p-8">
    <div class="max-w-3xl mx-auto bg-white p-10 rounded-xl shadow-sm">
        <header class="mb-8 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-900">{{ $appName }}</h1>
            <p class="text-sm text-gray-500">Last Updated: {{ now()->format('F j, Y') }}</p>
        </header>

        <div class="prose">
            {!! $content !!}
        </div>

        <footer class="mt-12 pt-6 border-t text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
        </footer>
    </div>
</body>

</html>