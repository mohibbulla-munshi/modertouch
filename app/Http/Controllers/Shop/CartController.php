<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Cart, CartItem, Product, ProductVariant, Coupon};
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getOrCreateCart(): Cart
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
            // Merge session cart on login
            $sessionId = session()->getId();
            $guestCart = Cart::where('session_id', $sessionId)->where('user_id', null)->first();
            if ($guestCart) {
                foreach ($guestCart->items as $item) {
                    $existing = $cart->items()->where('product_id', $item->product_id)
                        ->where('variant_id', $item->variant_id)->first();
                    if ($existing) {
                        $existing->increment('quantity', $item->quantity);
                    } else {
                        $cart->items()->create($item->only(['product_id', 'variant_id', 'quantity', 'price']));
                    }
                }
                $guestCart->delete();
            }
            return $cart;
        }

        return Cart::firstOrCreate(['session_id' => session()->getId(), 'user_id' => null]);
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.images', 'items.variant']);

        $coupon   = $cart->coupon_code ? Coupon::where('code', $cart->coupon_code)->first() : null;
        $subtotal = $cart->subtotal;
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $total    = max(0, $subtotal - $discount);

        return view('shop.cart.index', compact('cart', 'coupon', 'subtotal', 'discount', 'total'));
    }

    public function count()
    {
        $cart  = $this->getOrCreateCart();
        $items = [];
        if ($cart) {
            foreach ($cart->items as $item) {
                // Return keyed by product_id for simple lookup
                $items[$item->product_id] = $item->quantity;
            }
        }
        return response()->json([
            'count' => $cart ? $cart->item_count : 0,
            'items' => $items,
        ]);
    }


    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'nullable|integer|min:1|max:100',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $variant  = $request->variant_id ? ProductVariant::find($request->variant_id) : null;
        $price    = $variant?->price ?? $product->current_price;
        $quantity = $request->quantity ?? 1;

        $cart = $this->getOrCreateCart();
        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'quantity'   => $quantity,
                'price'      => $price,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'count' => $cart->fresh()->item_count]);
        }

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:100']);
        $item->update(['quantity' => $request->quantity]);
        return redirect()->route('cart.index');
    }

    public function updateItemQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'required|integer|min:0|max:100',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $variant  = $request->variant_id ? ProductVariant::find($request->variant_id) : null;
        $price    = $variant?->price ?? $product->current_price;
        $quantity = $request->quantity;

        $cart = $this->getOrCreateCart();
        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($quantity <= 0) {
            if ($item) {
                $item->delete();
            }
        } else {
            if ($item) {
                $item->update(['quantity' => $quantity]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'variant_id' => $request->variant_id,
                    'quantity'   => $quantity,
                    'price'      => $price,
                ]);
            }
        }

        $items = [];
        foreach ($cart->fresh()->items as $cartItem) {
            $items[$cartItem->product_id] = $cartItem->quantity;
        }

        return response()->json([
            'success' => true,
            'count'   => $cart->fresh()->item_count,
            'items'   => $items
        ]);
    }

    public function remove(CartItem $item)
    {
        $item->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);
        $cart   = $this->getOrCreateCart();
        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (! $coupon || ! $coupon->isValid($cart->subtotal)) {
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        $cart->update(['coupon_code' => $coupon->code]);
        return back()->with('success', "Coupon applied! You saved " . number_format($coupon->calculateDiscount($cart->subtotal), 2) . " BDT.");
    }

    public function removeCoupon()
    {
        $cart = $this->getOrCreateCart();
        $cart->update(['coupon_code' => null]);
        return back()->with('success', 'Coupon removed.');
    }
}
