@extends('layouts.store', ['title' => 'Admin Products'])

@section('content')
    <section class="shop-card p-6">
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-[#8a6f5a]">Admin</p>
                <h2 class="text-3xl font-semibold">Manage Products</h2>
            </div>
            <a href="{{ route('admin.products.create') }}" class="shop-btn">Create Product</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-2 text-sm">
                <thead>
                    <tr class="text-left text-[#7d6d61]">
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Slug</th>
                        <th class="px-3 py-2">Price</th>
                        <th class="px-3 py-2">Stock</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="rounded-xl bg-white">
                            <td class="px-3 py-3 font-medium">{{ $product->name }}</td>
                            <td class="px-3 py-3 text-[#6f6055]">{{ $product->slug }}</td>
                            <td class="px-3 py-3">{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            <td class="px-3 py-3">{{ $product->stock }}</td>
                            <td class="px-3 py-3">{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="px-3 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="shop-btn-muted">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="shop-btn-muted" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-3 py-6 text-[#6f6055]" colspan="6">No product data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </section>
@endsection
