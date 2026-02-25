<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Tag};
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'images']);

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->tag) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $request->tag));
        }
        if ($request->min_price) $query->where('price', '>=', $request->min_price);
        if ($request->max_price) $query->where('price', '<=', $request->max_price);
        if ($request->in_stock)  $query->inStock();

        switch ($request->sort) {
            case 'price_asc':  $query->orderBy('price'); break;
            case 'price_desc': $query->orderByDesc('price'); break;
            case 'name_asc':   $query->orderBy('name'); break;
            case 'newest':
            default:           $query->latest(); break;
        }

        $products   = $query->paginate(16)->withQueryString();
        $categories = Category::whereNull('parent_id')->where('is_active', true)->withCount('products')->get();
        $tags       = Tag::withCount('products')->orderByDesc('products_count')->take(20)->get();

        return view('shop.products.index', compact('products', 'categories', 'tags'));
    }

    public function category(Category $category)
    {
        $products = Product::active()
            ->where('category_id', $category->id)
            ->with('images')->latest()->paginate(16);

        $relatedCategories = Category::where('parent_id', $category->parent_id)
            ->where('id', '!=', $category->id)->where('is_active', true)->get();

        return view('shop.products.category', compact('category', 'products', 'relatedCategories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $product->increment('views');
        $product->load(['images', 'variants', 'reviews.user', 'tags', 'category', 'tabs']);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')->latest()->take(4)->get();

        $isInWishlist = auth()->check()
            ? auth()->user()->wishlist()->where('product_id', $product->id)->exists()
            : false;

        return view('shop.products.show', compact('product', 'related', 'isInWishlist'));
    }
}
