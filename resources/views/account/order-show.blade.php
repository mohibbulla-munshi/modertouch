@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="breadcrumb-section">
    <div class="container" style="max-width:1280px">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.orders') }}">My Orders</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </div>
</div>

<section style="padding:32px 0 64px">
    <div class="container" style="max-width:1100px">
        <div class="row g-4">
            <div class="col-lg-3">@include('account._sidebar', ['active'=>'orders'])</div>

            <div class="col-lg-9">
                {{-- Back + Title --}}
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="fw-700 mb-0" style="color:var(--primary)">
                        <i class="bi bi-receipt me-2" style="color:var(--teal)"></i>{{ $order->order_number }}
                    </h5>
                    <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Orders
                    </a>
                </div>

                @php
                    $statusMap = ['pending'=>'warning','confirmed'=>'info','processing'=>'primary','shipped'=>'secondary','delivered'=>'success','cancelled'=>'danger'];
                    $payMap    = ['pending'=>'warning','paid'=>'success','failed'=>'danger','refunded'=>'secondary'];
                @endphp

                {{-- Status + Payment banner --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3">
                        <div class="row g-3 text-center">
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.8px">Order Status</div>
                                <span class="badge bg-{{ $statusMap[$order->status] ?? 'secondary' }} mt-1 text-capitalize py-1 px-3">{{ $order->status }}</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.8px">Payment</div>
                                <span class="badge bg-{{ $payMap[$order->payment_status] ?? 'secondary' }} mt-1 text-capitalize py-1 px-3">{{ $order->payment_status }}</span>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.8px">Method</div>
                                <div class="fw-600 mt-1" style="font-size:.85rem">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.8px">Placed On</div>
                                <div class="fw-600 mt-1" style="font-size:.82rem">{{ $order->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- Left column --}}
                    <div class="col-md-8">

                        {{-- Order Items --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header">Order Items ({{ $order->items->count() }})</div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:var(--text-2);padding:10px 16px">Product</th>
                                                <th class="text-center" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:var(--text-2);white-space:nowrap">Qty</th>
                                                <th class="text-center" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:var(--text-2);white-space:nowrap">Unit</th>
                                                <th class="text-end" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:var(--text-2);white-space:nowrap;padding:10px 16px">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td style="padding:12px 16px">
                                                    <div class="fw-600" style="font-size:.875rem">{{ $item->product_name }}</div>
                                                    @if($item->variant_name)
                                                        <div class="text-muted" style="font-size:.75rem">{{ $item->variant_name }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-600">{{ $item->quantity }}</td>
                                                <td class="text-center text-muted" style="font-size:.85rem">৳{{ number_format($item->price/100,2) }}</td>
                                                <td class="text-end fw-700" style="padding:12px 16px;color:var(--teal)">
                                                    ৳{{ number_format($item->subtotal,2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Status Timeline --}}
                        @if($order->statusHistory->isNotEmpty())
                        <div class="card border-0 shadow-sm">
                            <div class="card-header">Status Timeline</div>
                            <div class="card-body p-3">
                                <div style="position:relative;padding-left:20px">
                                    @foreach($order->statusHistory->sortByDesc('created_at') as $h)
                                    <div style="position:relative;padding-bottom:18px">
                                        <div style="position:absolute;left:-20px;top:4px;width:10px;height:10px;border-radius:50%;background:var(--teal)"></div>
                                        @if(!$loop->last)
                                        <div style="position:absolute;left:-16px;top:14px;width:2px;bottom:0;background:var(--border)"></div>
                                        @endif
                                        <div class="fw-600 text-capitalize" style="font-size:.875rem">{{ $h->status }}</div>
                                        @if($h->comment)
                                            <div class="text-muted" style="font-size:.8rem">{{ $h->comment }}</div>
                                        @endif
                                        <div class="text-muted" style="font-size:.72rem">
                                            {{ $h->created_at->format('d M Y, h:i A') }}
                                            @if($h->changedBy)
                                                · by {{ $h->changedBy->name }}
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                    {{-- Right column --}}
                    <div class="col-md-4">

                        {{-- Financials --}}
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header">Order Summary</div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2" style="font-size:.875rem">
                                    <span class="text-muted">Subtotal</span>
                                    <span>৳{{ number_format($order->subtotal/100,2) }}</span>
                                </div>
                                @if($order->discount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success" style="font-size:.875rem">
                                    <span>Discount @if($order->coupon_code)<span class="badge" style="background:rgba(16,185,129,.12);color:#059669;font-size:.65rem">{{ $order->coupon_code }}</span>@endif</span>
                                    <span>−৳{{ number_format($order->discount/100,2) }}</span>
                                </div>
                                @endif
                                @if($order->shipping_cost > 0)
                                <div class="d-flex justify-content-between mb-2" style="font-size:.875rem">
                                    <span class="text-muted">Shipping</span>
                                    <span>৳{{ number_format($order->shipping_cost/100,2) }}</span>
                                </div>
                                @endif
                                @if($order->tax > 0)
                                <div class="d-flex justify-content-between mb-2" style="font-size:.875rem">
                                    <span class="text-muted">Tax</span>
                                    <span>৳{{ number_format($order->tax/100,2) }}</span>
                                </div>
                                @endif
                                <hr class="my-2">
                                <div class="d-flex justify-content-between fw-700" style="font-size:1rem">
                                    <span>Total</span>
                                    <span style="color:var(--teal)">৳{{ number_format($order->total/100,2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header">Shipping Address</div>
                            <div class="card-body p-3" style="font-size:.875rem;line-height:1.7;color:var(--text-2)">
                                <div class="fw-600 text-dark">{{ $order->shipping_name }}</div>
                                <div>{{ $order->shipping_phone }}</div>
                                <div>{{ $order->shipping_address }}</div>
                                <div>{{ $order->shipping_city }}@if($order->shipping_state), {{ $order->shipping_state }}@endif @if($order->shipping_postal){{ $order->shipping_postal }}@endif</div>
                                <div>{{ $order->shipping_country }}</div>
                            </div>
                        </div>

                        {{-- Notes --}}
                        @if($order->notes)
                        <div class="card border-0 shadow-sm">
                            <div class="card-header">Order Notes</div>
                            <div class="card-body p-3" style="font-size:.875rem;color:var(--text-2)">{{ $order->notes }}</div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
