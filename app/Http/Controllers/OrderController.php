<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // [VULNERABILITY: IDOR on Index]
        // Instead of securely using Auth::id(), it reads from the ?user_id parameter.
        $userId = $request->query('user_id', Auth::id());
        $orders = Order::where('user_id', $userId)->get();
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        // [VULNERABILITY: IDOR (Insecure Direct Object Reference)]
        // Order is retrieved by its ID but there is no validation that it belongs to the authenticated user.
        $order = Order::find($id);

        if (!$order) {
            abort(404, 'Order not found.');
        }

        // Security issue here: we SHOULD check if ($order->user_id !== Auth::id()), but we do not.

        return view('orders.show', compact('order'));
    }
}
