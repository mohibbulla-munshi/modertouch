@extends('layouts.app')
@section('title', 'Order Placed!')
@section('content')
<div class="container py-5 text-center" style="max-width:600px">
    <div class="card border-0 shadow-lg" style="border-radius:20px; padding:50px 40px">
        <div style="width:80px; height:80px; background:linear-gradient(135deg,#28a745,#20c997); border-radius:50%; display:flex; align-items:center; justify-content:center; margin: 0 auto 24px">
            <i class="bi bi-check-lg" style="font-size:2.5rem; color:#fff"></i>
        </div>
        <h2 class="fw-bold mb-2" style="color:#1B3A5C">Order Placed!</h2>
        <p class="text-muted mb-4">Thank you for your order. We will contact you soon to confirm.</p>

        <div class="card bg-light border-0 p-4 mb-4 text-start">
            <div class="row g-2">
                <div class="col-6 text-muted small">Order Number:</div>
                <div class="col-6 fw-bold" style="color:#1B3A5C">{{ $order->order_number }}</div>
                <div class="col-6 text-muted small">Payment Method:</div>
                <div class="col-6">{{ ucfirst(str_replace('_',' ',$order->payment_method)) }}</div>
                <div class="col-6 text-muted small">Total:</div>
                <div class="col-6 fw-bold fs-5" style="color:#1B3A5C">৳ {{ number_format($order->total, 0) }}</div>
                <div class="col-6 text-muted small">Status:</div>
                <div class="col-6"><span class="badge bg-warning-subtle text-warning">Pending</span></div>
            </div>
        </div>

        @if($order->payment_method === 'bank_transfer')
        <div class="alert alert-info text-start mb-4">
            <strong><i class="bi bi-info-circle me-2"></i>Bank Transfer Details</strong><br>
            <small>Please transfer <strong>৳ {{ number_format($order->total, 0) }}</strong> to:<br>
            Bank: {{ \App\Models\Setting::getValue('bank_name', 'Dutch-Bangla Bank') }}<br>
            Account: {{ \App\Models\Setting::getValue('bank_account', 'XXXX-XXXX-XXXX') }}<br>
            Reference: <strong>{{ $order->order_number }}</strong></small>
        </div>
        @endif

        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('track-order.index', ['order' => $order->order_number]) }}" class="btn btn-primary px-4" style="border-radius:30px">Track Order</a>
            <a href="{{ route('shop') }}" class="btn btn-outline-primary px-4" style="border-radius:30px">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection
