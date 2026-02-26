@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="breadcrumb-section">
    <div class="container" style="max-width:1280px">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">My Orders</li>
        </ol>
    </div>
</div>

<section style="padding:32px 0 64px">
    <div class="container" style="max-width:1100px">
        <div class="row g-4">
            <div class="col-lg-3">@include('account._sidebar', ['active'=>'orders'])</div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="fw-700 mb-0" style="color:var(--primary)">
                        <i class="bi bi-receipt me-2" style="color:var(--teal)"></i>My Orders
                    </h5>
                    {{-- Filter by status --}}
                    <form method="GET" action="{{ route('account.orders') }}" class="d-flex gap-2 align-items-center">
                        <select name="status" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @if($orders->isEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-bag-x" style="font-size:2.5rem;color:var(--text-3)"></i>
                            <p class="mt-3 text-muted mb-3">No orders found.</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary btn-sm"><i class="bi bi-bag me-1"></i>Shop Now</a>
                        </div>
                    </div>
                @else
                    {{-- Mobile-first card list --}}
                    <div class="d-flex flex-column gap-3">
                        @foreach($orders as $order)
                        @php
                            $statusMap = ['pending'=>['warning','clock'],'confirmed'=>['info','check'],'processing'=>['primary','gear'],'shipped'=>['secondary','truck'],'delivered'=>['success','check-circle'],'cancelled'=>['danger','x-circle']];
                            [$sc,$si] = $statusMap[$order->status] ?? ['secondary','circle'];
                            $payMap = ['pending'=>'warning','paid'=>'success','failed'=>'danger','refunded'=>'secondary'];
                        @endphp
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                {{-- Header row --}}
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 p-3 pb-2" style="border-bottom:1px solid var(--border)">
                                    <div>
                                        <div class="fw-700" style="font-size:.92rem">
                                            <i class="bi bi-receipt me-1" style="color:var(--teal)"></i>
                                            {{ $order->order_number }}
                                        </div>
                                        <div class="text-muted" style="font-size:.75rem">
                                            Placed: {{ $order->created_at->format('d M Y, h:i A') }}
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-{{ $sc }} bg-opacity-10 text-{{ $sc }} py-1 px-2 text-capitalize border border-{{ $sc }} border-opacity-25">
                                            <i class="bi bi-{{ $si }} me-1"></i>{{ $order->status }}
                                        </span>
                                        <span class="badge bg-{{ $payMap[$order->payment_status] ?? 'secondary' }} bg-opacity-10 text-{{ $payMap[$order->payment_status] ?? 'secondary' }} py-1 px-2 text-capitalize border border-{{ $payMap[$order->payment_status] ?? 'secondary' }} border-opacity-25">
                                            {{ $order->payment_status }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Items preview (max 2) --}}
                                <div class="px-3 py-2">
                                    @foreach($order->items->take(2) as $item)
                                    <div class="d-flex justify-content-between align-items-center py-1" style="font-size:.82rem">
                                        <span class="text-truncate me-3" style="max-width:220px">
                                            {{ $item->product_name }}
                                            @if($item->variant_name)<span class="text-muted"> · {{ $item->variant_name }}</span>@endif
                                        </span>
                                        <span class="text-nowrap text-muted">{{ $item->quantity }}× ৳{{ number_format($item->price/100,2) }}</span>
                                    </div>
                                    @endforeach
                                    @if($order->items->count() > 2)
                                    <div class="text-muted" style="font-size:.75rem">+{{ $order->items->count()-2 }} more item(s)</div>
                                    @endif
                                </div>

                                {{-- Footer row --}}
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 flex-wrap gap-2" style="background:var(--surface-2);border-top:1px solid var(--border);border-radius:0 0 var(--radius-lg) var(--radius-lg)">
                                    <div class="d-flex gap-3 flex-wrap" style="font-size:.8rem;color:var(--text-2)">
                                        <span><i class="bi bi-credit-card me-1"></i>{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</span>
                                        @if($order->coupon_code)
                                        <span class="text-success"><i class="bi bi-tag me-1"></i>{{ $order->coupon_code }}</span>
                                        @endif
                                        <span>{{ $order->items->count() }} item(s)</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-700" style="color:var(--teal);font-size:.95rem">৳{{ number_format($order->total/100,2) }}</span>
                                        <a href="{{ route('account.orders.show',$order) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($orders->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
