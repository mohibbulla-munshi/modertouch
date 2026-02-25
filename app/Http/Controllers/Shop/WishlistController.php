<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Wishlist, Product};
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $items = auth()->user()->wishlist()->with('product.images')->get();
        return view('account.wishlist', compact('items'));
    }

    public function toggle(Product $product)
    {
        $existing = auth()->user()->wishlist()->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->delete();
            $message = "Removed from wishlist.";
            $inWishlist = false;
        } else {
            auth()->user()->wishlist()->create(['product_id' => $product->id]);
            $message = "Added to wishlist!";
            $inWishlist = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'in_wishlist' => $inWishlist, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
