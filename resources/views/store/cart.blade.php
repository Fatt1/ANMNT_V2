@extends('layouts.store', ['title' => 'Cart'])

@section('content')
    <section class="shop-card p-6">
        <h2 class="text-3xl font-semibold">Your Cart</h2>

        @if (count($items) === 0)
            <p class="mt-3 text-sm text-[#66564d]">Cart is empty. Add products from listing page.</p>
        @else
            <div class="mt-5 space-y-4">
                @foreach ($items as $item)
                    <div class="flex flex-col gap-3 rounded-xl border border-[#eadccf] p-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $item['product']->name }}</h3>
                            <p class="text-sm text-[#5d5047]">Qty: {{ $item['quantity'] }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <p class="font-semibold text-brand-700">{{ number_format($item['line_total'], 0, ',', '.') }} VND</p>
                            <form action="{{ route('store.cart.remove', $item['product']->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="shop-btn-muted">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex items-center justify-between border-t border-[#e7d8ca] pt-4">
                <p class="text-sm text-[#66574c]">Subtotal</p>
                <p class="text-2xl font-bold text-brand-700">{{ number_format($total, 0, ',', '.') }} VND</p>
            </div>
        @endif
    </section>
@endsection
