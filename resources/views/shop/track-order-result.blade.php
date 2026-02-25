@extends('layouts.app')
@section('title', 'Order ' . $order->order_number . ' | Modern Touch BD')
@section('content')

<div class="bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Order: <span class="text-primary">{{ $order->order_number }}</span></h2>
            <a href="{{ route('track-order.index') }}" class="btn btn-outline-secondary btn-sm">Track Another Order</a>
        </div>

        <div class="row gy-4">
            <div class="col-lg-8">
                <!-- Status Timeline -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Status</h5>
                        @php
                            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                            $currentIndex = array_search($order->status, $statuses);
                            if ($currentIndex === false && $order->status !== 'cancelled') $currentIndex = 0;
                        @endphp

                        @if($order->status === 'cancelled')
                        <div class="alert alert-danger text-center fw-bold">
                            <i class="bi bi-x-circle-fill me-2"></i> This order has been cancelled.
                        </div>
                        @else
                        <div class="position-relative m-4">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($currentIndex / 3) * 100 }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between position-absolute top-0 w-100" style="margin-top: -12px;">
                                @foreach($statuses as $index => $status)
                                <div class="text-center" style="width: 70px; margin-left: -22px;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white {{ $index <= $currentIndex ? 'bg-primary' : 'bg-secondary' }}" style="width: 28px; height: 28px; font-size: 12px; margin: 0 auto;">
                                        @if($index < $currentIndex)
                                            <i class="bi bi-check"></i>
                                        @elseif($index === $currentIndex)
                                            <i class="bi bi-circle-fill" style="font-size: 8px;"></i>
                                        @else
                                            <i class="bi bi-circle" style="font-size: 8px;"></i>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-muted fw-bold" style="font-size: 12px; text-transform: capitalize;">{{ $status }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Items in Your Order</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td style="width: 80px;">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product_name }}" class="img-fluid rounded border">
                                            @else
                                                <div class="bg-secondary rounded border d-flex align-items-center justify-content-center" style="height: 80px;">
                                                    <i class="bi bi-image text-white fs-4"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                            @if($item->variant_name)
                                                <small class="text-muted">Variant: {{ $item->variant_name }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">
                                            {{ $item->quantity }} x ৳{{ number_format($item->price, 0) }}
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            ৳{{ number_format($item->subtotal, 0) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">৳{{ number_format($order->subtotal, 0) }}</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Discount</span>
                            <span class="fw-bold text-success">-৳{{ number_format($order->discount, 0) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="fw-bold">৳{{ number_format($order->shipping_cost, 0) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary">৳{{ number_format($order->total, 0) }}</span>
                        </div>
                        
                        <div class="bg-light p-3 rounded text-center">
                            <small class="text-muted d-block mb-1">Payment Method</small>
                            <span class="badge bg-secondary text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success ms-1">PAID</span>
                            @else
                                <span class="badge bg-warning text-dark ms-1">UNPAID</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Details -->
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Shipping Details</h5>
                        <p class="mb-1 fw-bold">{{ $order->shipping_name }}</p>
                        <p class="mb-1 text-muted">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                        <p class="mb-1 text-muted">{{ $order->shipping_state }} {{ $order->shipping_postal }}</p>
                        <p class="mb-0 text-muted"><i class="bi bi-telephone-fill me-2"></i> {{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
