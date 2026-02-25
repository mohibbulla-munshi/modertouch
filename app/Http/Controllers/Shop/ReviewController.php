<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Review, Product, Order};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        // Only customers who ordered can review
        $hasPurchased = auth()->user()->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->where('status', 'delivered')->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'You can only review products you have purchased and received.');
        }

        $data = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title'  => 'nullable|string|max:150',
            'body'   => 'nullable|string|max:1000',
        ]);

        $review = Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => auth()->id()],
            [...$data, 'is_approved' => false]
        );

        return back()->with('success', 'Review submitted! It will appear after moderation.');
    }
}
