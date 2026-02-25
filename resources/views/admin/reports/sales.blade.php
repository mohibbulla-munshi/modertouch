@extends('layouts.admin')
@section('title', 'Sales Report')
@section('breadcrumb')
<li class="breadcrumb-item active">Sales Reports</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-bar-chart-line me-2" style="color:var(--teal)"></i>Sales Report</h4>
        <div class="page-header-sub">Revenue & order analytics</div>
    </div>
    <a href="{{ route('admin.reports.export') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-primary">
        <i class="bi bi-download me-1"></i>Export CSV
    </a>
</div>
<div class="admin-card mb-4">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <label style="font-size:.8rem;font-weight:700;color:var(--text-2)">From</label>
                <input type="date" name="start_date" class="form-control" style="width:160px;height:38px" value="{{ request('start_date') }}">
            </div>
            <div class="d-flex align-items-center gap-2">
                <label style="font-size:.8rem;font-weight:700;color:var(--text-2)">To</label>
                <input type="date" name="end_date" class="form-control" style="width:160px;height:38px" value="{{ request('end_date') }}">
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="height:38px"><i class="bi bi-funnel me-1"></i>Apply</button>
            @if(request()->hasAny(['start_date','end_date']))
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-sm" style="height:38px;background:var(--surface-2);border:1px solid var(--border);color:var(--text-2)"><i class="bi bi-x me-1"></i>Clear</a>
            @endif
        </form>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-revenue">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Total Revenue</div><div class="stat-value">৳{{ number_format($summary->total_revenue ?? 0, 0) }}</div></div>
                <div class="stat-icon" style="background:rgba(13,115,119,.1)"><i class="bi bi-currency-exchange" style="color:var(--teal)"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-orders">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Total Orders</div><div class="stat-value">{{ $summary->total_orders ?? 0 }}</div></div>
                <div class="stat-icon" style="background:rgba(240,165,0,.1)"><i class="bi bi-receipt" style="color:var(--gold)"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-products">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Avg Order Value</div><div class="stat-value">৳{{ number_format($summary->avg_order_value ?? 0, 0) }}</div></div>
                <div class="stat-icon" style="background:rgba(16,185,129,.1)"><i class="bi bi-graph-up-arrow" style="color:#10B981"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-card-customers">
            <div class="d-flex align-items-start justify-content-between">
                <div><div class="stat-label">Total Discount</div><div class="stat-value">৳{{ number_format($summary->total_discount ?? 0, 0) }}</div></div>
                <div class="stat-icon" style="background:rgba(139,92,246,.1)"><i class="bi bi-tags" style="color:#8B5CF6"></i></div>
            </div>
        </div>
    </div>
</div>
<div class="admin-card">
    <div class="card-header"><span><i class="bi bi-calendar3 me-2" style="color:var(--teal)"></i>Daily Sales Breakdown</span></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Date</th><th>Orders</th><th>Revenue</th><th>Avg Value</th></tr></thead>
            <tbody>
                @forelse($byDay as $day)
                <tr>
                    <td style="font-weight:500;font-size:.875rem">{{ \Carbon\Carbon::parse($day->date)->format('d M Y, D') }}</td>
                    <td><span style="background:rgba(13,115,119,.1);color:var(--teal);padding:2px 10px;border-radius:20px;font-size:.78rem;font-weight:700">{{ $day->orders }}</span></td>
                    <td style="font-weight:700;font-family:'Inter',sans-serif;color:var(--primary)">৳{{ number_format($day->revenue, 0) }}</td>
                    <td style="font-size:.84rem;color:var(--text-2)">৳{{ $day->orders > 0 ? number_format($day->revenue / $day->orders, 0) : 0 }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5" style="color:var(--text-3)"><i class="bi bi-bar-chart-line fs-2 d-block mb-2" style="opacity:.4"></i>No data for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
