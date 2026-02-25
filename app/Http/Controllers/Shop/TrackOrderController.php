<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index(Request $request)
    {
        $orderNumber = $request->query('order', '');
        return view('shop.track-order', compact('orderNumber'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number'   => 'required|string',
            'email_or_phone' => 'required|string',
        ]);

        $searchTerm = $request->email_or_phone;

        $order = Order::where('order_number', $request->order_number)
            ->where(function($query) use ($searchTerm) {
                $query->where('guest_email', $searchTerm)
                      ->orWhere('guest_phone', $searchTerm)
                      ->orWhere('shipping_phone', $searchTerm)
                      ->orWhereHas('user', function($q) use ($searchTerm) {
                          $q->where('email', $searchTerm)
                            ->orWhere('phone', $searchTerm);
                      });
            })
            ->with(['items.product', 'items.variant', 'statusHistory'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found. Please check your Order Number and Email/Phone.')->withInput();
        }

        return view('shop.track-order-result', compact('order'));
    }
}
