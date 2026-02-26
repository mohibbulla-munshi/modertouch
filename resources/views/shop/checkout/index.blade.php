@extends('layouts.app')
@section('title', 'Checkout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        box-shadow: none !important;
        border-color: var(--bs-border-color);
    }
</style>
@endpush

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
                                <select name="city_id" id="citySelect" class="form-select @error('city_id') is-invalid @enderror" required>
                                    <option value=""></option>
                                    @foreach($cities as $cty)
                                    <option value="{{ $cty->id }}" data-cost="{{ $cty->shipping_cost }}" {{ old('city_id') == $cty->id ? 'selected' : '' }}>
                                        {{ $cty->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('city_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address_line1" class="form-control @error('address_line1') is-invalid @enderror" value="{{ old('address_line1') }}" required placeholder="Street address, area">
                                @error('address_line1')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        @forelse($paymentMethods as $index => $method)
                            <div class="form-check border p-3 rounded mb-2 @error('payment_method') border-danger @enderror">
                                <input class="form-check-input" type="radio" name="payment_method" value="{{ $method->type }}" id="pay_{{ $method->type }}" {{ (old('payment_method') == $method->type) || ($index == 0 && !old('payment_method')) ? 'checked' : '' }}>
                                <label class="form-check-label w-100 cursor-pointer" for="pay_{{ $method->type }}" style="cursor: pointer;">
                                    <strong>{{ $method->name }}</strong><br>
                                    @if($method->description)
                                        <small class="text-muted d-block">{{ $method->description }}</small>
                                    @endif
                                    
                                    @if($method->instructions)
                                        <div class="payment-instructions mt-2 p-2 bg-light rounded text-dark small" style="display: none; border-left: 3px solid var(--teal)">
                                            {!! nl2br(e($method->instructions)) !!}
                                        </div>
                                    @endif
                                </label>
                            </div>
                        @empty
                            <div class="alert alert-warning">No payment methods available right now.</div>
                        @endforelse
                        
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
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Shipping Cost</span><span id="displayShippingCost">৳ 0</span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4"><span>Total</span><span style="color:#1B3A5C" id="displayTotal">৳ {{ number_format($total, 0) }}</span></div>
                        <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:700; font-size:1.05rem">
                            Place Order <i class="bi bi-check-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 with Bootstrap 5 theme
    $('#citySelect').select2({
        theme: 'bootstrap-5',
        placeholder: "— Select City —",
        width: '100%',
        allowClear: true
    });

    const displayShipping = document.getElementById('displayShippingCost');
    const displayTotal = document.getElementById('displayTotal');
    const baseTotal = {{ $total }};

    function updateCosts() {
        let shippingCost = 0;
        const selectedOption = $('#citySelect').find(':selected');
        
        if (selectedOption.length && selectedOption.val() !== "") {
            shippingCost = parseFloat(selectedOption.data('cost')) || 0;
        }

        const finalTotal = baseTotal + shippingCost;
        
        displayShipping.innerText = '৳ ' + new Intl.NumberFormat('en-IN').format(shippingCost);
        displayTotal.innerText = '৳ ' + new Intl.NumberFormat('en-IN').format(finalTotal);
    }

    // Bind event to Select2 change
    $('#citySelect').on('change', updateCosts);
    
    // Call once on load
    updateCosts();
    // Handle dynamic payment instructions visibility
    function updatePaymentInstructions() {
        $('.payment-instructions').slideUp(200);
        const selectedPayment = $('input[name="payment_method"]:checked');
        if (selectedPayment.length) {
            selectedPayment.siblings('label').find('.payment-instructions').slideDown(200);
        }
    }
    
    $('input[name="payment_method"]').on('change', updatePaymentInstructions);
    updatePaymentInstructions(); // Run on load
});
</script>
@endpush
@endsection
