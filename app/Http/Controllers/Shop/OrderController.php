<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);
        return view('account.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load(['items', 'statusHistory.changedBy']);
        return view('account.order-show', compact('order'));
    }
}

