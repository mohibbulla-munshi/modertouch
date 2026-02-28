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
        
        $flashSale = \App\Models\FlashSale::active()->with(['products.product' => function($q) {
            $q->active()->with(['category', 'images']);
        }])->first();

        return view('shop.home', compact('sliders', 'categories', 'featuredProducts', 'newProducts', 'flashSale'));
    }

    public function sitemap()
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static pages
        $xml .= "<url><loc>" . url('/') . "</loc><changefreq>daily</changefreq><priority>1.0</priority></url>";
        $xml .= "<url><loc>" . url('/shop') . "</loc><changefreq>daily</changefreq><priority>0.9</priority></url>";
        $xml .= "<url><loc>" . url('/contact') . "</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>";

        // Categories
        Category::where('is_active', true)->each(function ($cat) use (&$xml) {
            $loc = route('shop.category', $cat->slug);
            $lm  = $cat->updated_at->toAtomString();
            $xml .= "<url><loc>{$loc}</loc><lastmod>{$lm}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>";
        });

        // Products
        Product::active()->each(function ($p) use (&$xml) {
            $loc = route('shop.product', $p->slug);
            $lm  = $p->updated_at->toAtomString();
            $xml .= "<url><loc>{$loc}</loc><lastmod>{$lm}</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>";
        });

        $xml .= '</urlset>';
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        return response(
            "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /cart/\nDisallow: /checkout/\nDisallow: /account/\nDisallow: /login\nDisallow: /register\nDisallow: /forgot-password\nDisallow: /reset-password\n\nSitemap: " . url('/sitemap.xml'),
            200
        )->header('Content-Type', 'text/plain');
    }
}
