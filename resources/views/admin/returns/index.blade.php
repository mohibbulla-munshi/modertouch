@extends('layouts.admin')
@section('title', 'Return Requests')
@section('breadcrumb')
<li class="breadcrumb-item active">Returns</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4><i class="bi bi-arrow-counterclockwise me-2" style="color:var(--teal)"></i>Return Requests (RMA)</h4>
        <div class="page-header-sub">Manage customer returns, damages, and refund processing.</div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">All Return Requests</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>RMA ID</th>
                    <th>Date</th>
                    <th>Order/Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Refund</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $ret)
                <tr>
                    <td>#{{ $ret->id }}</td>
                    <td>{{ $ret->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="fw-600">Order #{{ $ret->order_id }}</div>
                        <div class="small text-muted">{{ $ret->user->name }}</div>
                    </td>
                    <td>
                        <div class="fw-500">{{ $ret->product->name }}</div>
                        <div class="small text-muted">Reason: {{ $ret->reason }}</div>
                    </td>
                    <td>{{ $ret->quantity }}</td>
                    <td>
                        <span class="badge @if($ret->status == 'pending') bg-warning-subtle text-warning @elseif($ret->status == 'refunded') bg-success-subtle text-success @elseif($ret->status == 'rejected') bg-danger-subtle text-danger @else bg-primary-subtle text-primary @endif px-2">
                            {{ strtoupper($ret->status) }}
                        </span>
                    </td>
                    <td class="fw-700">৳{{ number_format($ret->refund_amount, 0) }}</td>
                    <td>
                        <a href="{{ route('admin.returns.show', $ret->id) }}" class="btn btn-sm btn-outline-primary">Manage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No return requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer border-0 bg-transparent py-3 text-center">
        {{ $returns->links() }}
    </div>
</div>
@endsection
