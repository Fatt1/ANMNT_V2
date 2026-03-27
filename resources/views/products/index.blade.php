@extends('layouts.storefront', ['title' => 'Sản phẩm - BrewMart'])

@section('content')
    <div class="bg-[#866a56] text-white rounded-xl mb-12 overflow-hidden shadow-sm">
        <div class="px-6 py-12 sm:px-12 lg:px-16 text-center">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                Cà phê & Trà Thượng Hạng
            </h2>
            <p class="mt-4 text-lg max-w-2xl mx-auto text-[#ebd4cc]">
                Khám phá hương vị tuyệt hảo từ những hạt cà phê được chọn lọc kỹ càng.
            </p>
        </div>
    </div>

    <div class="mb-8 flex items-center justify-between">
        <h2 class="text-2xl font-bold tracking-tight text-[#4c413b]">Sản phẩm nổi bật</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-6 xl:gap-x-8">
        
        @forelse ($products as $product)
        <div class="shop-card group flex flex-col overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="aspect-w-3 aspect-h-4 bg-[#ebd4cc] sm:aspect-none sm:h-64 overflow-hidden">
                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/400x400.png?text=BrewMart' }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-center object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            <div class="flex-1 p-5 flex flex-col space-y-3">
                <h3 class="text-lg font-semibold text-[#4c413b]">
                    <a href="#" class="hover:text-[#866a56]">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{ $product->name }}
                    </a>
                </h3>
                <p class="text-sm text-gray-500 line-clamp-2 flex-1">{{ $product->description }}</p>
                <div class="pt-2 border-t border-[#ebd4cc] flex items-center justify-between">
                    <p class="text-lg font-bold text-[#866a56]">${{ number_format($product->price, 2) }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 shop-card">
            <p class="text-lg text-[#866a56]">Chưa có sản phẩm nào trên kệ.</p>
        </div>
        @endforelse

    </div>
@endsection