<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Cart, Order, OrderItem, OrderStatusHistory, Coupon, Product, City, PaymentMethod};
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
        $cities = City::where('is_active', true)->orderBy('name')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('id')->get();

        return view('shop.checkout.index', compact('cart', 'coupon', 'subtotal', 'discount', 'total', 'addresses', 'cities', 'paymentMethods'));
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
            'city_id'        => 'required|exists:cities,id',
            'payment_method' => 'required|exists:payment_methods,type',
            'notes'          => 'nullable|string|max:500',
        ]);

        $city = City::findOrFail($data['city_id']);

        $cart->load(['items.product', 'items.variant']);
        $coupon   = $cart->coupon_code ? Coupon::where('code', $cart->coupon_code)->first() : null;
        $subtotal = $cart->subtotal;
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $shipping = $city->shipping_cost;
        $total    = max(0, $subtotal - $discount) + $shipping;

        // If user is not logged in but provides an email that exists in the system,
        // silently link the order to that user account.
        $userId = auth()->id();
        $isGuest = !auth()->check();

        if ($isGuest) {
            $existingUser = \App\Models\User::where('email', $data['email'])->first();
            if ($existingUser) {
                $userId = $existingUser->id;
                $isGuest = false;
            }
        }

        $order = Order::create([
            'user_id'          => $userId,
            'guest_name'       => $isGuest ? $data['name'] : null,
            'guest_email'      => $isGuest ? $data['email'] : null,
            'guest_phone'      => $isGuest ? $data['phone'] : null,
            'shipping_name'    => $data['name'],
            'shipping_phone'   => $data['phone'],
            'shipping_address' => $data['address_line1'],
            'shipping_city'    => $city->name,
            'shipping_state'   => null,
            'shipping_postal'  => null,
            'shipping_country' => 'Bangladesh',
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'shipping_cost'    => $shipping,
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
            // Deduct stock safely to prevent BIGINT UNSIGNED error on zero stock
            if ($item->product->stock > 0) {
                $item->product->decrement('stock', min($item->quantity, $item->product->stock));
            }
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
