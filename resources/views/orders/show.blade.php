@extends('layouts.store', ['title' => 'Order Details'])

@section('content')
<div class="max-w-4xl mx-auto shop-card p-6 mt-10">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold">Order #{{ $order->id }}</h2>
        <a href="{{ route('orders.index') }}" class="text-sm shop-btn-muted">&larr; Back to Orders</a>
    </div>
    
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Order Date</p>
                <p class="font-medium">{{ $order->created_at ? $order->created_at->format('F d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-medium text-green-600">{{ ucfirst($order->status) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Amount</p>
                <p class="font-medium text-lg">{{ number_format($order->total_amount, 0, ',', '.') }} VND</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Shipping Address</p>
                <p class="font-medium">{{ $order->shipping_address ?: 'N/A' }}</p>
            </div>
            <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-2">Invoice</p>
                @if($order->invoice_file)
                    <a href="{{ route('invoice.download', ['file' => $order->invoice_file]) }}" class="inline-flex items-center text-brand-600 hover:text-brand-800">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download Invoice ({{ $order->invoice_file }})
                    </a>
                @else
                    <p class="text-sm text-orange-600">No invoice available.</p>
                @endif
            <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-2">Send Invoice by Email</p>
                <form action="{{ route('invoice.send_email') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="order_id" value="{{ $order->id }}" class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm p-2 border" placeholder="Order ID">
                    <button type="submit" class="shop-btn-muted !bg-white border">Send Email</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="mt-8 text-sm text-gray-500 italic text-center">
        This page demonstrates IDOR (Insecure Direct Object Reference). Notice how you can change the ID in the URL to view other users' orders without permission checks.
    </div>
</div>
@endsection
