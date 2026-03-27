@php
    $isEdit = isset($product);
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="name" class="mb-1 block text-sm font-medium">Product Name</label>
        <input class="shop-input" type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
    </div>

    <div>
        <label for="slug" class="mb-1 block text-sm font-medium">Slug</label>
        <input class="shop-input" type="text" id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" required>
    </div>

    <div>
        <label for="image_url" class="mb-1 block text-sm font-medium">Image URL</label>
        <input class="shop-input" type="url" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url ?? '') }}">
    </div>

    <div>
        <label for="price" class="mb-1 block text-sm font-medium">Price</label>
        <input class="shop-input" type="number" min="0" step="0.01" id="price" name="price" value="{{ old('price', $product->price ?? '0') }}" required>
    </div>

    <div>
        <label for="stock" class="mb-1 block text-sm font-medium">Stock</label>
        <input class="shop-input" type="number" min="0" id="stock" name="stock" value="{{ old('stock', $product->stock ?? '0') }}" required>
    </div>

    <div class="md:col-span-2">
        <label for="description" class="mb-1 block text-sm font-medium">Description</label>
        <textarea class="shop-input min-h-28" id="description" name="description">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2 flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm">Active product</label>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button class="shop-btn" type="submit">{{ $isEdit ? 'Update Product' : 'Create Product' }}</button>
    <a class="shop-btn-muted" href="{{ route('admin.products.index') }}">Cancel</a>
</div>
