@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
<li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h4>
            <i class="bi bi-receipt me-2" style="color:var(--teal)"></i>
            Order <span style="color:var(--teal)">{{ $order->order_number }}</span>
        </h4>
        <div class="page-header-sub">
            Placed {{ $order->created_at->format('d F Y, h:i A') }}
            &nbsp;·&nbsp;
            <span class="badge-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-outline-primary btn-sm" target="_blank">
            <i class="bi bi-download me-1"></i>Invoice PDF
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
            <i class="bi bi-arrow-left me-1"></i>Back to Orders
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- ── LEFT COLUMN ──────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Order Items --}}
        <div class="admin-card mb-4">
            <div class="card-header">
                <span><i class="bi bi-box-seam me-2" style="color:var(--teal)"></i>Order Items</span>
                <span style="font-family:'Inter',sans-serif;font-size:.78rem;color:var(--text-3);font-weight:500">
                    {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                </span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty × Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:.875rem;color:var(--text)">{{ $item->product_name }}</div>
                                @if($item->variant_name)
                                    <div style="font-size:.75rem;color:var(--teal);margin-top:2px">
                                        <i class="bi bi-diagram-2 me-1"></i>{{ $item->variant_name }}
                                    </div>
                                @endif
                            </td>
                            <td style="color:var(--text-2);font-size:.875rem">
                                {{ $item->quantity }} &times;
                                <span style="font-weight:600">৳{{ number_format($item->price, 0) }}</span>
                            </td>
                            <td class="text-end" style="font-weight:700;font-family:'Inter',sans-serif;color:var(--primary)">
                                ৳{{ number_format($item->subtotal, 0) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="border-top:2px solid var(--border)">
                        @if($order->discount > 0)
                        <tr>
                            <td colspan="2" class="text-end" style="color:var(--text-2);font-size:.84rem">Discount</td>
                            <td class="text-end" style="color:#10B981;font-weight:600">-৳{{ number_format($order->discount, 0) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="text-end" style="color:var(--text-2);font-size:.84rem">Shipping</td>
                            <td class="text-end" style="font-weight:600">৳{{ number_format($order->shipping_cost, 0) }}</td>
                        </tr>
                        <tr style="background:rgba(13,115,119,.04)">
                            <td colspan="2" class="text-end" style="font-weight:700;font-size:1rem;font-family:'Inter',sans-serif">Total</td>
                            <td class="text-end" style="font-weight:800;font-size:1.1rem;font-family:'Inter',sans-serif;color:var(--teal)">
                                ৳{{ number_format($order->total, 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="admin-card mb-4">
            <div class="card-header">
                <span><i class="bi bi-geo-alt me-2" style="color:var(--teal)"></i>Shipping Address</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin-bottom:4px">Recipient</div>
                        <div style="font-weight:700;font-size:.95rem">{{ $order->shipping_name }}</div>
                        <div style="color:var(--text-2);font-size:.84rem;margin-top:2px">
                            <i class="bi bi-telephone me-1" style="color:var(--teal)"></i>{{ $order->shipping_phone }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin-bottom:4px">Address</div>
                        <div style="font-size:.875rem;color:var(--text-2);line-height:1.7">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}@if($order->shipping_state), {{ $order->shipping_state }}@endif
                            @if($order->shipping_postal) {{ $order->shipping_postal }}@endif
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="col-12">
                        <div style="background:rgba(240,165,0,.08);border:1px solid rgba(240,165,0,.25);border-radius:8px;padding:12px 14px">
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--gold);margin-bottom:4px">
                                <i class="bi bi-chat-left-text me-1"></i>Order Notes
                            </div>
                            <div style="font-size:.875rem;color:var(--text-2)">{{ $order->notes }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Order Timeline --}}
        <div class="admin-card">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2" style="color:var(--teal)"></i>Order Timeline</span>
            </div>
            <div class="card-body" style="padding:0">
                @foreach($order->statusHistory as $history)
                <div class="d-flex align-items-start gap-3 px-4 py-3" style="border-bottom:1px solid var(--border)">
                    {{-- Timeline dot --}}
                    <div style="width:10px;height:10px;border-radius:50%;background:var(--teal);flex-shrink:0;margin-top:6px;box-shadow:0 0 0 3px rgba(13,115,119,.18)"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                            <div>
                                <span class="badge-status-{{ $history->status }}">{{ ucfirst($history->status) }}</span>
                                @if($history->comment)
                                    <span style="font-size:.84rem;color:var(--text-2);margin-left:8px">"{{ $history->comment }}"</span>
                                @endif
                            </div>
                            <div style="font-size:.75rem;color:var(--text-3)">
                                {{ $history->created_at->format('d M Y, H:i') }}
                                &nbsp;by&nbsp;
                                <span style="font-weight:600;color:var(--text-2)">{{ $history->changedBy->name ?? 'System' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ──────────────────────────── --}}
    <div class="col-lg-4">

        {{-- Process Order --}}
        <div class="admin-card mb-4" style="border-color:var(--teal)">
            <div class="card-header" style="background:rgba(13,115,119,.08);border-bottom-color:rgba(13,115,119,.25)">
                <span style="color:var(--teal)"><i class="bi bi-arrow-repeat me-2"></i>Process Order</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Current Status --}}
                    <div class="mb-3">
                        <div class="form-label">Current Status</div>
                        <span class="badge-status-{{ $order->status }}" style="font-size:.8rem;padding:6px 14px">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    {{-- Update Status --}}
                    <div class="mb-3">
                        <label class="form-label">Update Status To</label>
                        <select name="status" class="form-select">
                            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Comment --}}
                    <div class="mb-3">
                        <label class="form-label">Comment (Optional)</label>
                        <textarea name="comment" class="form-control" rows="2"
                            placeholder="Tracking ID, courier name, notes..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Payment & Totals --}}
        <div class="admin-card mb-4">
            <div class="card-header">
                <span><i class="bi bi-credit-card me-2" style="color:var(--teal)"></i>Payment & Totals</span>
            </div>
            <div class="card-body" style="padding:0">
                <div style="padding:16px 20px;border-bottom:1px solid var(--border)" class="d-flex justify-content-between">
                    <span style="color:var(--text-2);font-size:.875rem">Subtotal</span>
                    <span style="font-weight:600">৳{{ number_format($order->subtotal, 0) }}</span>
                </div>
                @if($order->discount > 0)
                <div style="padding:16px 20px;border-bottom:1px solid var(--border)" class="d-flex justify-content-between">
                    <span style="color:var(--text-2);font-size:.875rem">Discount</span>
                    <span style="font-weight:600;color:#10B981">-৳{{ number_format($order->discount, 0) }}</span>
                </div>
                @endif
                <div style="padding:16px 20px;border-bottom:1px solid var(--border)" class="d-flex justify-content-between">
                    <span style="color:var(--text-2);font-size:.875rem">Shipping</span>
                    <span style="font-weight:600">৳{{ number_format($order->shipping_cost, 0) }}</span>
                </div>
                <div style="padding:16px 20px;background:rgba(13,115,119,.04);border-bottom:1px solid var(--border)" class="d-flex justify-content-between align-items-center">
                    <span style="font-weight:800;font-family:'Inter',sans-serif;font-size:1rem">Total</span>
                    <span style="font-weight:800;font-family:'Inter',sans-serif;font-size:1.2rem;color:var(--teal)">
                        ৳{{ number_format($order->total, 0) }}
                    </span>
                </div>
                <div style="padding:16px 20px">
                    <div style="display:grid;grid-template-columns:auto 1fr;gap:6px 12px;font-size:.84rem">
                        <span style="color:var(--text-3)">Method</span>
                        <span style="font-weight:600;color:var(--text)">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        <span style="color:var(--text-3)">Status</span>
                        <span>
                            <span class="{{ $order->payment_status === 'paid' ? 'badge-status-delivered' : 'badge-status-pending' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer Details --}}
        <div class="admin-card">
            <div class="card-header">
                <span><i class="bi bi-person-badge me-2" style="color:var(--teal)"></i>Customer</span>
                @if($order->user)
                    <a href="{{ route('admin.customers.show', $order->user) }}" class="btn btn-sm btn-outline-primary" style="font-size:.72rem">View Profile</a>
                @endif
            </div>
            <div class="card-body">
                {{-- Avatar + Name --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;flex-shrink:0;font-family:'Inter',sans-serif">
                        {{ strtoupper(substr($order->shipping_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.95rem">{{ $order->shipping_name }}</div>
                        <div style="font-size:.75rem;color:var(--text-3)">
                            @if($order->user)
                                <i class="bi bi-person-check me-1" style="color:var(--teal)"></i>Registered User
                            @else
                                <i class="bi bi-person-x me-1"></i>Guest Checkout
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
                <div style="display:grid;gap:8px;font-size:.84rem">
                    @if($order->user?->email ?? $order->guest_email)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope" style="color:var(--teal);width:16px"></i>
                        <span style="color:var(--text-2)">{{ $order->user?->email ?? $order->guest_email }}</span>
                    </div>
                    @endif
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone" style="color:var(--teal);width:16px"></i>
                        <span style="color:var(--text-2)">{{ $order->shipping_phone }}</span>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt" style="color:var(--teal);width:16px;margin-top:2px"></i>
                        <span style="color:var(--text-2);line-height:1.6">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}@if($order->shipping_state), {{ $order->shipping_state }}@endif
                        </span>
                    </div>
                </div>

                @if($order->user)
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin-bottom:8px">Order History</div>
                    <div class="d-flex gap-3">
                        <div style="text-align:center">
                            <div style="font-weight:800;font-family:'Inter',sans-serif;font-size:1.1rem;color:var(--primary)">{{ $order->user->orders()->count() }}</div>
                            <div style="font-size:.72rem;color:var(--text-3)">Total Orders</div>
                        </div>
                        <div style="text-align:center">
                            <div style="font-weight:800;font-family:'Inter',sans-serif;font-size:1.1rem;color:var(--teal)">৳{{ number_format($order->user->orders()->sum('total'), 0) }}</div>
                            <div style="font-size:.72rem;color:var(--text-3)">Lifetime Value</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
