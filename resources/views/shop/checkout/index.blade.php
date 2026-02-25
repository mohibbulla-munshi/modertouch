@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="container py-4" style="max-width:900px">
    <h2 class="fw-bold mb-4" style="color:#1B3A5C">Checkout</h2>
    <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:12px">
                    <div class="card-header fw-bold" style="font-weight:600; background:transparent">Shipping Information</div>
                    <div class="card-body">
                        @if($addresses->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-weight:600">Use saved address</label>
                            @foreach($addresses as $addr)
                            <div class="form-check border rounded p-2 mb-2" style="cursor:pointer" onclick="fillAddress({{ $addr->id }})">
                                <input class="form-check-input" type="radio" name="saved_address" value="{{ $addr->id }}" id="addr{{ $addr->id }}">
                                <label class="form-check-label ms-1" for="addr{{ $addr->id }}" style="cursor:pointer">
                                    <strong>{{ $addr->label }}</strong> — {{ $addr->name }}, {{ $addr->address_line1 }}, {{ $addr->city }}
                                </label>
                            </div>
                            @endforeach
                            <hr>
                        </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()?->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()?->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()?->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required placeholder="Dhaka">
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address_line1" class="form-control @error('address_line1') is-invalid @enderror" value="{{ old('address_line1') }}" required placeholder="Street address, area">
                                @error('address_line1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">State / Division</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Order Notes (optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Special instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm" style="border-radius:12px">
                    <div class="card-header fw-bold" style="font-weight:600; background:transparent">Payment Method</div>
                    <div class="card-body">
                        <div class="form-check border p-3 rounded mb-2 @error('payment_method') border-danger @enderror">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                            <label class="form-check-label" for="cod">
                                <i class="bi bi-cash-coin me-2 text-success"></i>
                                <strong>Cash on Delivery</strong><br>
                                <small class="text-muted">Pay when you receive your order</small>
                            </label>
                        </div>
                        <div class="form-check border p-3 rounded">
                            <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="bank">
                            <label class="form-check-label" for="bank">
                                <i class="bi bi-bank me-2 text-primary"></i>
                                <strong>Bank Transfer</strong><br>
                                <small class="text-muted">Bank details provided after order confirmation</small>
                            </label>
                        </div>
                        @error('payment_method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="border-radius:12px; top:80px">
                    <div class="card-header fw-bold" style="font-weight:600; background:transparent">Order Summary</div>
                    <div class="card-body">
                        @foreach($cart->items as $item)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span>{{ Str::limit($item->product->name, 35) }} <span class="badge bg-light text-dark">×{{ $item->quantity }}</span></span>
                            <span class="fw-600">৳ {{ number_format($item->price * $item->quantity, 0) }}</span>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><span>৳ {{ number_format($subtotal, 0) }}</span></div>
                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success"><span>Coupon ({{ $coupon->code }})</span><span>-৳ {{ number_format($discount, 0) }}</span></div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4"><span>Total</span><span style="color:#1B3A5C">৳ {{ number_format($total, 0) }}</span></div>
                        <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:700; font-size:1.05rem">
                            Place Order <i class="bi bi-check-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
