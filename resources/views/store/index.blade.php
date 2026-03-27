@extends('layouts.store', ['title' => 'Products'])

@section('content')
    <section class="mb-6">
        <div class="shop-card p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-[#8a6f5a]">Curated Collection</p>
            <h2 class="mt-2 text-4xl font-semibold leading-tight">Products For Everyday Commerce Labs</h2>
            <p class="mt-2 max-w-3xl text-sm text-[#6d5c52]">A realistic e-commerce listing layout with cards, pricing and quick actions.</p>
        </div>
    </section>

    <section>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($products as $product)
                <article class="shop-card overflow-hidden">
                    <a href="{{ route('products.show', ['id' => $product->id]) }}" class="block h-48 overflow-hidden bg-[#f5e6d9]">
                        <img src="{{ $product->image_url ?: 'https://picsum.photos/600/400?random='.$product->id }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-500 hover:scale-110">
                    </a>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold">{{ $product->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-[#6a5a50]">{{ $product->description ?: 'No description available.' }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-lg font-bold text-brand-700">{{ number_format($product->price, 0, ',', '.') }} VND</span>
                            <a href="{{ route('products.show', ['id' => $product->id]) }}" class="shop-btn inline-block text-center">View Details</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="shop-card col-span-full p-8 text-center text-[#66564d]">
                    Chua co san pham. Vao trang admin de tao du lieu.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </section>
@endsection
