@extends('layouts.admin')
@section('title', 'Inventory Movement')
@section('breadcrumb')
<li class="breadcrumb-item active">Inventory</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4><i class="bi bi-box-seam me-2" style="color:var(--teal)"></i>Inventory Ledger</h4>
        <div class="page-header-sub">Track every stock change across your warehouse in real-time.</div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Stock Items</div>
            <div class="stat-value">{{ number_format($totalStock) }}</div>
            <div class="stat-badge text-success">Active SKUs</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Stock-In (Last 30 Days)</div>
            <div class="stat-value text-success">+{{ number_format($stockIn) }}</div>
            <div class="stat-badge text-muted">Procurements / Returns</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Stock-Out (Last 30 Days)</div>
            <div class="stat-value text-danger">-{{ number_format($stockOut) }}</div>
            <div class="stat-badge text-muted">Sales / Damage</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Low Stock Alerts</div>
            <div class="stat-value text-warning">{{ $lowStockCount }}</div>
            <div class="stat-badge text-danger">Action Required</div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">Recent Stock Movements</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Balance</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M H:i') }}</td>
                    <td>
                        <div class="fw-600">{{ $log->product->name }}</div>
                        <div class="small text-muted">SKU: {{ $log->product->sku ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $log->type === 'in' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-2">
                            {{ strtoupper($log->type) }}
                        </span>
                    </td>
                    <td class="fw-700">{{ $log->quantity }}</td>
                    <td>
                        <div class="fw-500">{{ $log->reason }}</div>
                        @if($log->reference_id)
                            <small class="text-muted">Ref: {{ $log->reference_id }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-muted">{{ $log->old_stock }}</span>
                            <i class="bi bi-arrow-right mx-1"></i>
                            <span class="fw-700">{{ $log->new_stock }}</span>
                        </div>
                    </td>
                    <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No inventory movements recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer border-0 bg-transparent py-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
