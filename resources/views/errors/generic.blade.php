<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Error</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="shop-shell">
    <div class="mx-auto flex min-h-screen max-w-xl items-center px-4">
        <div class="shop-card w-full p-8 text-center">
            <p class="text-xs uppercase tracking-[0.2em] text-[#8a6f5a]">Error {{ $status ?? 500 }}</p>
            <h1 class="mt-2 text-4xl font-semibold">{{ $message ?? 'Request could not be completed' }}</h1>
            <p class="mt-3 text-sm text-[#695b51]">Please try again later or go back to the home page.</p>
            <a href="" class="shop-btn mt-6">Back To Store</a>
        </div>
    </div>
</body>
</html>
