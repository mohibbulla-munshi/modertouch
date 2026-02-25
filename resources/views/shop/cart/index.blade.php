@extends('layouts.app')
@section('title', 'Shopping Cart')
@section('content')
<div class="breadcrumb-section">
    <div class="container"><nav><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active">Cart</li></ol></nav></div>
</div>
<div class="container py-4">
    <h2 class="fw-bold mb-4" style="color:#1B3A5C">Shopping Cart</h2>
    @if($cart->items->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-cart-x" style="font-size:4rem; color:#ccc"></i>
        <h4 class="mt-3 text-muted">Your cart is empty</h4>
        <a href="{{ route('shop') }}" class="btn btn-primary mt-3 px-5" style="border-radius:30px">Start Shopping</a>
    </div>
    @else
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body p-0">
                    @foreach($cart->items as $item)
                    <div class="d-flex align-items-center p-3 border-bottom gap-3">
                        @php $img = $item->product->images->first()?->image_path ?? $item->product->featured_image; @endphp
                        <img src="{{ $img ? asset('storage/'.$img) : asset('images/no-image.png') }}" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:8px">
                        <div class="flex-grow-1">
                            <a href="{{ route('shop.product', $item->product->slug) }}" class="text-dark text-decoration-none fw-600" style="font-weight:600">{{ $item->product->name }}</a>
                            @if($item->variant)<div class="text-muted small">{{ $item->variant->name }}</div>@endif
                            <div class="fw-bold text-primary mt-1">৳ {{ number_format($item->price, 0) }}</div>
                        </div>
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-1">
                            @csrf @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm" style="width:70px" onchange="this.form.submit()">
                        </form>
                        <div class="fw-bold">৳ {{ number_format($item->price * $item->quantity, 0) }}</div>
                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            {{-- Coupon --}}
            <div class="card border-0 shadow-sm mt-3" style="border-radius:12px">
                <div class="card-body">
                    <h6 class="fw-600 mb-3" style="font-weight:600">Have a Coupon?</h6>
                    @if($coupon)
                    <div class="alert alert-success d-flex justify-content-between align-items-center py-2">
                        <span>Coupon <strong>{{ $coupon->code }}</strong> applied! Saving ৳{{ number_format($discount, 0) }}</span>
                        <form action="{{ route('cart.coupon.remove') }}" method="POST">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </div>
                    @else
                    <form action="{{ route('cart.coupon') }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code" style="max-width:250px">
                        <button type="submit" class="btn btn-outline-primary">Apply</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius:12px; top:80px">
                <div class="card-header fw-bold" style="font-weight:600; background:transparent; border-bottom:2px solid #f0f0f0">Order Summary</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><span>৳ {{ number_format($subtotal, 0) }}</span></div>
                    @if($discount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success"><span>Discount</span><span>-৳ {{ number_format($discount, 0) }}</span></div>
                    @endif
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Shipping</span><span class="text-success">Calculated at checkout</span></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span style="color:#1B3A5C">৳ {{ number_format($total, 0) }}</span></div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mt-4 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600">
                        Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
