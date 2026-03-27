<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::where('is_active', true)
            ->latest('id')
            ->paginate(12);

        return view('store.index', compact('products'));
    }

    public function show(Request $request)
    {
        $id = $request->query('id', '1');

        try {
            // [VULNERABILITY: SQL Injection (Union-Based / Blind)]
            // The unescaped parameter `$id` is directly appended to the query.
            $sql = "SELECT * FROM products WHERE id = " . $id;
            // $products = DB::select($sql);


            // $product = !empty($products) ? $products[0] : null;
            $product = Product::where('id', $id)->first();
            if (!$product) {
                abort(404, 'Product not found.');
            }

        } catch (\Throwable $e) {
            return abort(500, "Database Error: " . $e->getMessage());
        }

        // We fetch some related products to make UI look good
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id ?? 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Pass $product object to the existing view 'store.show'
        if ($product instanceof Product) {
            $productModel = $product;
        } else {
            $productModel = new Product((array) $product); // Cast it back to model for the view helper
            $productModel->id = $product->id; // Ensure ID is set
        }

        return view('store.show', ['product' => $productModel, 'relatedProducts' => $relatedProducts, 'sql' => $sql]);
    }

    public function applyCoupon(Request $request)
    {
        $code = $request->input('code', '');

        // [VULNERABILITY: Boolean-Based Blind SQLi]
        // This query relies entirely on true/false behavior. It swallows errors if syntax is invalid or returns a generic failure.
        try {
            //SUMMER20'# 
            $sql = "SELECT * FROM coupons WHERE code = '$code' AND is_active = 1";
            $results = DB::select($sql);
            // $sql_parameterized  = "SELECT * FROM coupons WHERE code = ? AND is_active = 1";
            // $results = DB::select($sql_parameterized, [$code]);
            if (count($results) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Áp dụng mã thành công, giảm ' . $results[0]->discount_percent . '%!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã không tồn tại hoặc đã hết hạn.'
                ]);
            }
        } catch (\Exception $e) {
            // Suppress exact errors intentionally for strict boolean-based blind scenario
            return response()->json([
                'success' => false,
                'message' => 'Mã không hợp lệ (lỗi xử lý).'
            ]);
        }
    }

    public function buy($id)
    {
        $product = Product::findOrFail($id);

        $order = new \App\Models\Order();
        $order->user_id = \Illuminate\Support\Facades\Auth::id();
        $order->total_amount = $product->price;
        $order->status = 'pending';
        $order->invoice_file = 'invoice_' . time() . '.pdf';
        // Add a dummy address for testing
        $order->shipping_address = '123 Fake Street, HCMC';
        $order->save();

        DB::table('order_items')->insert([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Generate the real PDF using barryvdh/laravel-dompdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoice.pdf', compact('order', 'product'));
        $invoiceDir = storage_path('app/private/invoices');
        if (!file_exists($invoiceDir)) {
            mkdir($invoiceDir, 0755, true);
        }
        $pdf->save($invoiceDir . '/' . $order->invoice_file);

        return redirect()->route('orders.index')->with('status', 'Đặt hàng thành công! Đơn hàng của bạn đã được ghi nhận và Hóa đơn đã được tạo.');
    }
}
