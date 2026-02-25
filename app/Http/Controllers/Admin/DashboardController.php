<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User, Inquiry, Review};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue'    => Order::where('status', '!=', 'cancelled')->sum('total'),
            'total_orders'     => Order::count(),
            'total_products'   => Product::active()->count(),
            'total_customers'  => User::where('role', 'customer')->count(),
            'pending_orders'   => Order::where('status', 'pending')->count(),
            'pending_reviews'  => Review::where('is_approved', false)->count(),
            'unread_inquiries' => Inquiry::unread()->count(),
            'low_stock'        => Product::lowStock()->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        $topProducts = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Revenue last 30 days for chart
        $salesChart = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date');

        $lowStockProducts = Product::lowStock()->with('category')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'salesChart', 'lowStockProducts'));
    }
}
