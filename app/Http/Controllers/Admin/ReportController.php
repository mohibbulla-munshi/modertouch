<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $summary = Order::whereBetween('created_at', [$from, $to])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('SUM(discount) as total_discount'),
                DB::raw('AVG(total) as avg_order_value')
            )->first();

        $byStatus = Order::whereBetween('created_at', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')->get();

        $byDay = Order::whereBetween('created_at', [$from, $to])
            ->where('status', '!=', 'cancelled')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')->orderBy('date')->get();

        $topProducts = DB::table('order_items')
            ->whereBetween('created_at', [$from, $to])
            ->select('product_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('product_name')->orderByDesc('revenue')->limit(10)->get();

        return view('admin.reports.sales', compact('summary', 'byStatus', 'byDay', 'topProducts', 'from', 'to'));
    }

    public function orders(Request $request)
    {
        $from   = $request->from ?? now()->startOfMonth()->toDateString();
        $to     = $request->to   ?? now()->toDateString();
        $orders = Order::with('user')->whereBetween('created_at', [$from, $to])
            ->latest()->paginate(30)->withQueryString();
        return view('admin.reports.orders', compact('orders', 'from', 'to'));
    }

    public function export(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $orders = Order::with(['items', 'user'])
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $filename = "orders-{$from}-to-{$to}.csv";
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="'.$filename.'"'];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order #', 'Customer', 'Email', 'Status', 'Payment', 'Total', 'Date']);
            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->order_number,
                    $o->shipping_name,
                    $o->user?->email ?? $o->guest_email,
                    $o->status,
                    $o->payment_method,
                    $o->total,
                    $o->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
