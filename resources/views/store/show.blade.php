@extends('layouts.store', ['title' => $product->name])

@section('content')
    <section class="grid gap-6 lg:grid-cols-2">
        <div class="shop-card overflow-hidden">
            <img src="{{ $product->image_url ?: 'https://picsum.photos/900/700?random='.$product->id }}" alt="{{ $product->name }}" class="h-full min-h-80 w-full object-cover">
        </div>

        <div class="shop-card p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-[#8a6f5a]">Product Detail</p>
            <h2 class="mt-2 text-4xl font-semibold">{{ $product->name }}</h2>
            <p class="mt-4 text-sm leading-relaxed text-[#5e4f46]">{{ $product->description ?: 'No detailed description yet.' }}</p>
            <p class="mt-5 text-3xl font-bold text-brand-700">{{ number_format($product->price, 0, ',', '.') }} VND</p>
            <p class="mt-2 text-sm text-[#64564d]">Stock: {{ $product->stock }}</p>

            <form action="{{ route('products.buy', $product->id) }}" method="POST">
                @csrf
                <button class="shop-btn w-full" type="submit">Buy Now</button>
            </form>
            
            <!-- Coupon UI (Boolean-Based Blind SQLi Target) -->
            <div class="mt-6 border-t border-[rgba(235,212,204,1)] pt-4">
                <p class="text-sm font-semibold mb-2 text-[#4c413b]">Promo Code</p>
                <div class="flex gap-2">
                    <input type="text" id="coupon-code" placeholder="Example: SUMMER20" class="flex-grow rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border">
                    <button type="button" onclick="applyCoupon()" class="shop-btn-muted text-sm border border-[rgba(235,212,204,1)]">Apply</button>
                </div>
                <p id="coupon-message" class="text-sm mt-2 font-medium"></p>
            </div>
        </div>
    </section>

    <section class="mt-8">
        <h3 class="mb-4 text-2xl font-semibold">Related Products</h3>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($relatedProducts as $related)
                <a href="{{ route('products.show', ['id' => $related->id]) }}" class="shop-card p-4 transition hover:-translate-y-1">
                    <h4 class="font-semibold">{{ $related->name }}</h4>
                    <p class="mt-2 text-sm text-[#6a5a50]">{{ number_format($related->price, 0, ',', '.') }} VND</p>
                </a>
            @endforeach
        </div>
    </section>

    <script>
        function applyCoupon() {
            const code = document.getElementById('coupon-code').value;
            const messageEl = document.getElementById('coupon-message');
            
            messageEl.classList.remove('text-green-600', 'text-red-600');
            messageEl.textContent = 'Applying...';

            fetch('{{ route("coupon.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Must be defined in layout or here if needed. We don't have csrf meta tag in layout yet. Wait...
                },
                body: JSON.stringify({ code: code, _token: '{{ csrf_token() }}' })
            })
            .then(res => res.json())
            .then(data => {
                messageEl.textContent = data.message;
                messageEl.classList.add(data.success ? 'text-green-600' : 'text-red-600');
            })
            .catch(error => {
                messageEl.textContent = 'Mã không tồn tại hoặc lỗi kết nối.';
                messageEl.classList.add('text-red-600');
            });
        }
    </script>
@endsection
