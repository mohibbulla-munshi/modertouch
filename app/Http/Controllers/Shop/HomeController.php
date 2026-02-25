<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Category, Product, Slider, Setting};


class HomeController extends Controller
{
    public function index()
    {
        $sliders        = Slider::active()->get();
        $categories     = Category::whereNull('parent_id')->where('is_active', true)
                            ->withCount('products')->orderBy('sort_order')->take(8)->get();
        $featuredProducts = Product::active()->featured()->with(['category', 'images'])->take(8)->get();
        $newProducts    = Product::active()->with(['category', 'images'])->latest()->take(8)->get();

        return view('shop.home', compact('sliders', 'categories', 'featuredProducts', 'newProducts'));
    }

    public function sitemap()
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $urls = [url('/'), url('/shop'), url('/contact')];
        foreach ($urls as $u) {
            $xml .= "<url><loc>{$u}</loc></url>";
        }

        Category::where('is_active', true)->each(function ($cat) use (&$xml) {
            $loc = route('shop.category', $cat->slug);
            $xml .= "<url><loc>{$loc}</loc></url>";
        });

        Product::active()->each(function ($p) use (&$xml) {
            $loc = route('shop.product', $p->slug);
            $lm  = $p->updated_at->toAtomString();
            $xml .= "<url><loc>{$loc}</loc><lastmod>{$lm}</lastmod><priority>0.7</priority></url>";
        });

        $xml .= '</urlset>';
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        return response(
            "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: " . url('/sitemap.xml'),
            200
        )->header('Content-Type', 'text/plain');
    }
}
