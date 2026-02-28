@extends('layouts.app')
@section('title', 'Request a Return')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-primary p-4 text-white">
                    <h4 class="mb-1 fw-700"><i class="bi bi-arrow-return-left me-2"></i>Request a Return</h4>
                    <p class="mb-0 opacity-75">Order #{{ $order->order_number }} • {{ $order->created_at->format('d M Y') }}</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('account.returns.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        
                        <div class="mb-4">
                            <label class="form-label fw-600">Which item would you like to return? <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                @foreach($order->items as $item)
                                <div class="col-12">
                                    <div class="p-3 border rounded-3 d-flex align-items-center gap-3 bg-light hover-shadow transition-sm">
                                        <input type="radio" name="product_id" value="{{ $item->product_id }}" id="prod_{{ $item->id }}" class="form-check-input" required>
                                        <label class="d-flex align-items-center gap-3 w-100 mb-0 cursor-pointer" for="prod_{{ $item->id }}">
                                            <img src="{{ $item->product->featured_image ? asset('storage/'.$item->product->featured_image) : 'https://via.placeholder.com/60' }}" class="rounded shadow-sm" style="width:50px;height:50px;object-fit:cover">
                                            <div class="flex-grow-1">
                                                <div class="fw-700 text-dark">{{ $item->product->name }}</div>
                                                <div class="small text-muted">Purchased Quantity: {{ $item->quantity }}</div>
                                            </div>
                                            <div class="fw-700 text-primary">৳{{ number_format($item->price/100, 0) }}</div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-600">Quantity to Return <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control rounded-3" value="1" min="1" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600">Reason for Return <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control rounded-3" rows="4" placeholder="Please describe why you are returning this item (e.g., Wrong size, Damaged on arrival, etc.)" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600">Upload Photos (Optional)</label>
                            <input type="file" name="images[]" class="form-control rounded-3" multiple accept="image/*">
                            <div class="form-text small">Upload photos of the product/damage to speed up the approval process. Max 2MB per image.</div>
                        </div>

                        <div class="d-flex gap-3 justify-content-end mt-5">
                            <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-700">Submit Return Request</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-warning-subtle text-warning-emphasis rounded-3 border border-warning-subtle small">
                <i class="bi bi-info-circle-fill me-2"></i><strong>Note:</strong> Once submitted, our team will review your request. If approved, you will receive instructions on how to send the item back. Refunds are credited to your <strong>ModernTouch Wallet</strong>.
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.transition-sm { transition: all 0.2s ease; }
.hover-shadow:hover { border-color: var(--primary) !important; background: #fff !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.form-check-input:checked + label { color: var(--primary); }
</style>
@endsection
