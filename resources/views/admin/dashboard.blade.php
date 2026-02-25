@extends('layouts.admin')
@section('title', 'Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-grid-1x2-fill me-2" style="color:var(--teal)"></i>Dashboard</h4>
        <div class="page-header-sub">{{ now()->format('l, d F Y') }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus me-1"></i>New Product</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-receipt me-1"></i>Orders</a>
    </div>
</div>

{{-- ── KPI Cards ────────────────────────────────────────────── --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-revenue">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon" style="background:rgba(13,115,119,.12)">
                    <i class="bi bi-currency-exchange" style="color:var(--teal)"></i>
                </div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-value">৳ {{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stat-badge text-success"><i class="bi bi-arrow-up-short"></i>All time</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-orders">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon" style="background:rgba(240,165,0,.12)">
                    <i class="bi bi-receipt" style="color:var(--gold)"></i>
                </div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            @if($stats['pending_orders'] > 0)
            <div class="stat-badge" style="color:var(--gold)"><i class="bi bi-clock me-1"></i>{{ $stats['pending_orders'] }} pending</div>
            @else
            <div class="stat-badge text-success"><i class="bi bi-check-circle me-1"></i>All fulfilled</div>
            @endif
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-products">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon" style="background:rgba(16,185,129,.12)">
                    <i class="bi bi-box-seam" style="color:#10B981"></i>
                </div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-value">{{ $stats['total_products'] }}</div>
            @if($stats['low_stock'] > 0)
            <div class="stat-badge text-danger"><i class="bi bi-exclamation-triangle me-1"></i>{{ $stats['low_stock'] }} low stock</div>
            @else
            <div class="stat-badge text-success"><i class="bi bi-check-circle me-1"></i>Stock OK</div>
            @endif
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card-customers">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon" style="background:rgba(139,92,246,.12)">
                    <i class="bi bi-people" style="color:#8B5CF6"></i>
                </div>
                <div class="stat-label">Customers</div>
            </div>
            <div class="stat-value">{{ $stats['total_customers'] }}</div>
            <div class="stat-badge" style="color:#8B5CF6"><i class="bi bi-person-check me-1"></i>Registered</div>
        </div>
    </div>
</div>

{{-- ── Charts + Quick Actions ──────────────────────────────── --}}
<div class="row g-4 mb-4">
    {{-- Revenue Chart --}}
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart-line me-2" style="color:var(--teal)"></i>Revenue — Last 30 Days</span>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline-primary btn-sm">Full Report</a>
            </div>
            <div class="card-body" style="padding:24px">
                <canvas id="revenueChart" style="height:260px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="card-header"><i class="bi bi-lightning-charge me-2" style="color:var(--teal)"></i>Quick Actions</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('admin.products.create') }}" class="quick-action-item">
                    <i class="bi bi-plus-circle"></i><span>Add New Product</span>
                </a>
                <a href="{{ route('admin.categories.create') }}" class="quick-action-item">
                    <i class="bi bi-folder-plus"></i><span>Add Category</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="quick-action-item">
                    <i class="bi bi-receipt"></i><span>Manage Orders
                    @if($stats['pending_orders'] > 0)
                    <span class="badge ms-auto" style="background:var(--gold);color:#fff;font-size:.65rem">{{ $stats['pending_orders'] }}</span>
                    @endif
                    </span>
                </a>
                <a href="{{ route('admin.sliders.create') }}" class="quick-action-item">
                    <i class="bi bi-images"></i><span>Add Slider</span>
                </a>
                <a href="{{ route('admin.coupons.create') }}" class="quick-action-item">
                    <i class="bi bi-ticket-perforated"></i><span>Create Coupon</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="quick-action-item">
                    <i class="bi bi-sliders"></i><span>Site Settings</span>
                </a>
                @if($stats['pending_reviews'] > 0)
                <a href="{{ route('admin.reviews.index') }}" class="quick-action-item" style="border-color:#EF4444">
                    <i class="bi bi-star" style="color:#EF4444"></i>
                    <span>Reviews <span class="badge ms-auto" style="background:#EF4444;color:#fff;font-size:.65rem">{{ $stats['pending_reviews'] }}</span></span>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── Recent Orders + Products ────────────────────────────── --}}
<div class="row g-4">
    {{-- Recent Orders --}}
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2" style="color:var(--teal)"></i>Recent Orders</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th></th>
                    </tr></thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td><span class="fw-600" style="font-size:.82rem;color:var(--primary)">#{{ $order->order_number }}</span></td>
                            <td>
                                <div style="font-size:.85rem;font-weight:600">{{ $order->shipping_name }}</div>
                                <div style="font-size:.75rem;color:var(--text-3)">{{ $order->user?->email ?? $order->guest_email }}</div>
                            </td>
                            <td><span class="fw-700" style="color:var(--primary)">৳ {{ number_format($order->total, 0) }}</span></td>
                            <td>
                                <span class="badge-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td style="font-size:.78rem;color:var(--text-3)">{{ $order->created_at->format('d M, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary table-row-actions">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5" style="color:var(--text-3)">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>No orders yet.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Products + Low Stock --}}
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="card-header"><i class="bi bi-trophy me-2" style="color:var(--gold)"></i>Top Selling</div>
            <div class="card-body p-0">
                @forelse($topProducts as $p)
                <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid var(--border)">
                    <div style="font-size:.84rem;font-weight:500;color:var(--text)">{{ Str::limit($p->product_name, 28) }}</div>
                    <span class="badge" style="background:rgba(13,115,119,.12);color:var(--teal);font-weight:700">{{ $p->total_qty }} sold</span>
                </div>
                @empty
                <p class="text-center py-4" style="color:var(--text-3);font-size:.84rem">No sales data yet.</p>
                @endforelse
            </div>
        </div>

        @if($lowStockProducts->isNotEmpty())
        <div class="admin-card">
            <div class="card-header" style="color:#DC2626"><i class="bi bi-exclamation-triangle me-2"></i>Low Stock Alert</div>
            <div class="card-body p-0">
                @foreach($lowStockProducts as $p)
                <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid var(--border)">
                    <div style="font-size:.84rem;font-weight:500">{{ Str::limit($p->name, 26) }}</div>
                    <span class="badge bg-danger">{{ $p->stock }} left</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
const labels = @json($salesChart->keys());
const data   = @json($salesChart->values());
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Revenue (৳)',
            data,
            borderColor: '#0D7377',
            backgroundColor: 'rgba(13,115,119,.08)',
            fill: true,
            tension: 0.45,
            pointBackgroundColor: '#0D7377',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(10,22,40,.95)',
                titleColor: '#F0A500',
                bodyColor: '#fff',
                padding: 12,
                cornerRadius: 8,
                callbacks: { label: c => ' ৳ ' + c.formattedValue }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { color: '#9CA3AF', font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { color: '#9CA3AF', font: { size: 11 } } }
        }
    }
});
</script>
@endpush
@endsection
