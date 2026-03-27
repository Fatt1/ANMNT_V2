@extends('layouts.store', ['title' => 'Dashboard'])

@section('content')
    <section class="grid gap-4 md:grid-cols-3">
        <div class="shop-card p-5">
            <p class="text-xs uppercase tracking-[0.2em] text-[#8a6f5a]">Orders</p>
            <p class="mt-2 text-4xl font-semibold">{{ $stats['orders'] }}</p>
        </div>
        <div class="shop-card p-5">
            <p class="text-xs uppercase tracking-[0.2em] text-[#8a6f5a]">Saved Items</p>
            <p class="mt-2 text-4xl font-semibold">{{ $stats['saved'] }}</p>
        </div>
        <div class="shop-card p-5">
            <p class="text-xs uppercase tracking-[0.2em] text-[#8a6f5a]">Total Spent</p>
            <p class="mt-2 text-4xl font-semibold">{{ number_format($stats['spent'], 0, ',', '.') }} VND</p>
        </div>
    </section>

    <section class="mt-8 shop-card p-6">
        <h2 class="text-2xl font-semibold">Recently Viewed</h2>
        @if ($recentlyViewed->isEmpty())
            <p class="mt-2 text-sm text-[#6a5b50]">No recently viewed products yet.</p>
        @else
            <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($recentlyViewed as $product)
                    <a class="rounded-xl border border-[#e6d7ca] bg-white p-4" href="{{ route('store.show', $product->slug) }}">
                        <h3 class="font-semibold">{{ $product->name }}</h3>
                        <p class="mt-1 text-sm text-[#6f6055]">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
