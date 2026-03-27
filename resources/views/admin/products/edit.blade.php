{{-- @extends('layouts.store', ['title' => 'Edit Product'])

@section('content')
    <section class="shop-card p-6">
        <h2 class="text-3xl font-semibold">Edit Product</h2>
        <p class="mt-1 text-sm text-[#6f6055]">Update product information.</p>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.products._form', ['product' => $product])
        </form>
    </section>
@endsection --}}
