<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'BrewMart - Ecom Lab' }}</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    
    <style type="text/tailwindcss">
        @layer components {
            .shop-shell { @apply bg-[#fdfbf9] text-[#4c413b] font-sans antialiased; }
            .shop-card { @apply bg-white rounded-xl shadow-sm border border-[#ebd4cc]; }
            .shop-btn { @apply inline-flex items-center justify-center bg-[#866a56] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#6a4030] transition; }
            .shop-btn-muted { @apply inline-flex items-center justify-center text-[#866a56] px-4 py-2 rounded-lg font-medium hover:bg-[#ebd4cc] transition; }
        }
    </style>
</head>
<body class="shop-shell">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <header class="mb-8">
            <div class="shop-card flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-[#866a56]">Ecom Lab</p>
                    <h1 class="text-3xl font-bold">BrewMart</h1>
                </div>
                <nav class="flex flex-wrap gap-3 items-center text-sm">
                    <a href="{{ route('products.index') }}" class="shop-btn-muted">Products</a>
                    @auth
                        <a href="{{ route('orders.index', ['user_id' => Auth::id()]) }}" class="shop-btn-muted">Orders</a>
                        <a href="{{ route('tickets.create') }}" class="shop-btn-muted">Support</a>
                        <span class="text-xs text-gray-500 ml-2">Hi, {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="shop-btn bg-red-600 hover:bg-red-800 ml-2">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="shop-btn bg-brand-600">Login</a>
                        <a href="{{ route('register') }}" class="shop-btn-muted">Register</a>
                    @endauth
                </nav>
            </div>
        </header>

        @if (session('status'))
            <div class="mb-6 rounded-xl border border-[#d8cfca] bg-white px-4 py-3 text-sm text-[#4c413b]">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-[#ebd4cc] bg-[#fff6f1] px-4 py-3 text-sm text-[#6a4030]">
                Dữ liệu gửi lên chưa hợp lệ. Vui lòng kiểm tra lại.
            </div>
        @endif

        @yield('content')
        
    </div>
</body>
</html>