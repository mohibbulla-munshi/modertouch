<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Cart, Order, OrderItem, OrderStatusHistory, Coupon, Product};
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $cart->load(['items.product', 'items.variant']);

        $coupon   = $cart->coupon_code ? Coupon::where('code', $cart->coupon_code)->first() : null;
        $subtotal = $cart->subtotal;
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total    = max(0, $subtotal - $discount);

        $addresses = auth()->check() ? auth()->user()->addresses()->get() : collect();

        return view('shop.checkout.index', compact('cart', 'coupon', 'subtotal', 'discount', 'total', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        $cart = $this->getCart();
        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'address_line1'  => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'nullable|string|max:100',
            'postal_code'    => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,bank_transfer',
            'notes'          => 'nullable|string|max:500',
        ]);

        $cart->load(['items.product', 'items.variant']);
        $coupon   = $cart->coupon_code ? Coupon::where('code', $cart->coupon_code)->first() : null;
        $subtotal = $cart->subtotal;
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total    = max(0, $subtotal - $discount);

        $order = Order::create([
            'user_id'          => auth()->id(),
            'guest_name'       => auth()->check() ? null : $data['name'],
            'guest_email'      => auth()->check() ? null : $data['email'],
            'guest_phone'      => auth()->check() ? null : $data['phone'],
            'shipping_name'    => $data['name'],
            'shipping_phone'   => $data['phone'],
            'shipping_address' => $data['address_line1'],
            'shipping_city'    => $data['city'],
            'shipping_state'   => $data['state'] ?? null,
            'shipping_postal'  => $data['postal_code'] ?? null,
            'shipping_country' => 'Bangladesh',
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'shipping_cost'    => 0,
            'total'            => $total,
            'coupon_code'      => $coupon?->code,
            'payment_method'   => $data['payment_method'],
            'notes'            => $data['notes'] ?? null,
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product_id,
                'variant_id'   => $item->variant_id,
                'product_name' => $item->product->name,
                'variant_name' => $item->variant?->name,
                'price'        => $item->price,
                'quantity'     => $item->quantity,
                'subtotal'     => $item->price * $item->quantity,
            ]);
            // Deduct stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Record initial status
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status'   => 'pending',
            'comment'  => 'Order placed.',
        ]);

        // Increment coupon usage
        if ($coupon) $coupon->increment('used_count');

        // Clear cart
        $cart->items()->delete();
        $cart->update(['coupon_code' => null]);

        // TODO: send confirmation email
        // Mail::to($data['email'])->send(new OrderConfirmationMail($order));

        return redirect()->route('checkout.success', $order)->with('success', 'Your order has been placed!');
    }

    public function success(Order $order)
    {
        $order->load('items');
        return view('shop.checkout.success', compact('order'));
    }

    private function getCart(): ?Cart
    {
        if (auth()->check()) {
            return Cart::where('user_id', auth()->id())->with('items')->first();
        }
        return Cart::where('session_id', session()->getId())->with('items')->first();
    }
}
