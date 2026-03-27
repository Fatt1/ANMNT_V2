<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; }
        .header { font-size: 24px; font-weight: bold; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        .details { margin-bottom: 20px; }
        .amount { color: #d32f2f; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">VulnShop - Customer Invoice</div>
    <div class="details">
        <p><strong>Order Number:</strong> #{{ $order->id }}</p>
        <p><strong>Date:</strong> {{ $order->created_at ? $order->created_at->format('d M Y') : date('d M Y') }}</p>
        <p><strong>Customer ID:</strong> {{ $order->user_id }}</p>
        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $product->name }}</td>
                <td>1</td>
                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">Grand Total:</td>
                <td class="amount">{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
            </tr>
        </tfoot>
    </table>
    
    <div style="margin-top: 40px; text-align: center; color: #666; font-size: 12px;">
        <p>This is a vulnerable learning environment.</p>
        <p>File generated on the server dynamically for testing Path Traversal vulnerabilities.</p>
    </div>
</body>
</html>
