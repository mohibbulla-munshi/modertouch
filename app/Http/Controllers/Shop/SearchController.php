<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->q;

        if (! $query) return redirect()->route('home');

        $products = Product::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhereHas('tags', fn($t) => $t->where('name', 'like', "%{$query}%"))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$query}%"));
            })
            ->with(['images', 'category'])
            ->paginate(15)
            ->withQueryString();

        return view('shop.search', compact('products', 'query'));
    }
}
