@extends('layouts.app')
@section('title', 'Track Your Order | Modern Touch BD')
@section('content')

<div class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-geo-alt-fill text-primary mb-2" style="font-size: 3rem;"></i>
                            <h2 class="fw-bold">Track Your Order</h2>
                            <p class="text-muted">Enter your order details below to check its current status.</p>
                        </div>

                        @if(session('error'))
                        <div class="alert alert-danger" style="border-radius:10px;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ route('track-order.track') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Order Number</label>
                                <input type="text" name="order_number" class="form-control form-control-lg @error('order_number') is-invalid @enderror" value="{{ old('order_number', $orderNumber ?? '') }}" placeholder="e.g. ORD-XYZ123" required>
                                @error('order_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Email or Phone Number</label>
                                <input type="text" name="email_or_phone" class="form-control form-control-lg @error('email_or_phone') is-invalid @enderror" value="{{ old('email_or_phone') }}" placeholder="Used during checkout" required>
                                @error('email_or_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" style="border-radius:10px;">
                                Track Order <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
