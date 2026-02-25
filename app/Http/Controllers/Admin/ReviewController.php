<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Review, ActivityLog};

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user'])->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);
        return back()->with('success', 'Review status updated.');
    }

    public function destroy(Review $review)
    {
        ActivityLog::record('Deleted review on: ' . $review->product?->name, $review);
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
