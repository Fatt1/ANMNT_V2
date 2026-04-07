<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // [LỖI IDOR - index]
        // Nếu lấy user_id từ query parameter, attacker có thể đổi ?user_id=... để đọc dữ liệu người khác.
        $userId = request()->query('user_id', Auth::id());

        // [CODE SỬA - NOTE]
        // Chỉ lấy user id từ session đăng nhập hiện tại (fix IDOR).
        // $userId = Auth::id();

        $orders = Order::where('user_id', $userId)->get();
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        // [LỖI IDOR - show]
        // Lỗi cũ: tìm theo id trực tiếp mà không check owner.
        $order = Order::find($id);

        if (!$order) {
            abort(404, 'Order not found.');
        }

        // [CODE SỬA - NOTE]
        // Bật đoạn này để chặn IDOR, user không thể xem order của người khác.
        // if ($order->user_id !== Auth::id()) {
        //     abort(403, 'Unauthorized');
        // }

        return view('orders.show', compact('order'));
    }
}
