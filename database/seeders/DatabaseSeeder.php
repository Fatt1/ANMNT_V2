<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. TẠO USERS (Mục tiêu: Lấy tài khoản Customer để thực hiện IDOR sang đơn hàng của Admin)
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'email' => 'admin@vulnshop.com',
            'password' => 'Admin@123!',
            'role' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $customerId = DB::table('users')->insertGetId([
            'name' => 'CTF Player',
            'email' => 'player@vulnshop.com',
            'password' => 'password',
            'role' => 'customer',
            'created_at' => $now,
            'updated_at' => $now,
        ]);


        // 3. TẠO PRODUCTS (Dữ liệu hiển thị trang chủ và là Vector tấn công SQLi)
        $products = [
            [
                'name' => 'Laptop Gaming X-Pro',
                'description' => 'Cấu hình khủng, tản nhiệt cực tốt. Phù hợp cho mọi loại game.',
                'price' => 1500.00,
                'stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Smartphone Z Ultra',
                'description' => 'Camera 108MP, màn hình OLED 120Hz mượt mà.',
                'price' => 899.99,
                'stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1598327105666-5b89351cb315?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tai nghe Bluetooth Noise Cancelling',
                'description' => 'Chống ồn chủ động, pin sử dụng liên tục 30 giờ.',
                'price' => 199.50,
                'stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Chèn sản phẩm và lấy ID của sản phẩm đầu tiên để làm Order Item
        DB::table('products')->insert($products);
        $firstProductId = DB::table('products')->first()->id;

        // 4. TẠO ORDERS (Mục tiêu: IDOR)
        // Đơn hàng số 1 (Của Admin): Chứa Flag bí mật trong địa chỉ giao hàng
        $adminOrderId = DB::table('orders')->insertGetId([
            'user_id' => $adminId,
            'total_amount' => 1500.00,
            'status' => 'completed',
            'shipping_address' => 'Hệ thống Quản trị - Secret: CTF{1d0r_0rd3r_byp4ss}', // FLAG NẰM Ở ĐÂY
            'invoice_file' => 'admin_invoice_top_secret.pdf',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Đơn hàng số 2 (Của Customer): Đơn hàng bình thường để họ vào xem hợp lệ
        $customerOrderId = DB::table('orders')->insertGetId([
            'user_id' => $customerId,
            'total_amount' => 199.50,
            'status' => 'pending',
            'shipping_address' => '273 An Dương Vương, Phường 3, Quận 5, TP.HCM',
            'invoice_file' => 'invoice_2026_03_27.pdf', // File mồi cho Path Traversal
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 5. TẠO ORDER ITEMS
        DB::table('order_items')->insert([
            [
                'order_id' => $adminOrderId,
                'product_id' => $firstProductId,
                'quantity' => 1,
                'price' => 1500.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'order_id' => $customerOrderId,
                'product_id' => $firstProductId + 2, // Lấy tai nghe
                'quantity' => 1,
                'price' => 199.50,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // 6. CREATE COUPONS
        DB::table('coupons')->insert([
            ['code' => 'SUMMER20', 'discount_percent' => 20, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'EXPIRED50', 'discount_percent' => 50, 'is_active' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
