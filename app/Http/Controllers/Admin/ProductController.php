<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    
    public function show(Request $request)
    {
       // Lấy ID từ URL, mặc định là 1 nếu không truyền
        $id = $request->query('id', '1');

        try {
            // [VULNERABLE HERE] — User input is concatenated directly into SQL query.
            $sql = "SELECT * FROM products WHERE id = " . $id;
            $products = DB::select($sql); 
            
            // Lấy ra phần tử đầu tiên nếu có dữ liệu
            $product = !empty($products) ? $products[0] : null;

            // KIỂM TRA 404: Nếu không có sản phẩm (hoặc câu SQL bị sai logic do Blind SQLi)
            if (!$product) {
                abort(404, 'Không tìm thấy sản phẩm này.');
            }

        } catch (\Throwable $e) {
            // Nếu học viên gõ sai cú pháp (ví dụ: /product?id=1' --), sẽ nhảy vào đây
            // Trong CTF, in thẳng lỗi ra màn hình để học viên dễ làm Error-Based SQLi
            return abort(500, "Database Error: " . $e->getMessage());
        }

        // Nếu tìm thấy, trả về view chi tiết
        return view('products.show', compact('product', 'sql'));
    }

    public function indexClient()
    {
        // Ở trang chủ, chúng ta dùng Eloquent ORM an toàn để lấy sản phẩm đang active
        // Lỗ hổng sẽ nằm ở trang chi tiết khi họ click vào sản phẩm
        $products = Product::where('is_active', true)->get();

        return view('products.index', compact('products'));
    }

    // ==========================================
    // ADMIN QUẢN LÝ SẢN PHẨM (CRUD MẶC ĐỊNH)
    // Cần bọc trong Middleware 'auth' và kiểm tra role 'admin' ở Route
    // ==========================================

    public function indexAdmin()
    {
        $products = Product::all();
        return view('products.admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('products.admin.products.create');
    }

    public function store(Request $request)
    {
        // Admin nhập dữ liệu chuẩn, không cần cố tình làm lỗi ở đây
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm.');
    }

    public function edit(Product $product)
    {
        return view('products.admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }
}
