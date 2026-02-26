@extends('layouts.admin')
@section('title', 'Add Payment Method')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.payment-methods.index') }}">Payment Methods</a></li>
<li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h4 class="mb-1"><i class="bi bi-plus-circle me-2" style="color:var(--teal)"></i>Add Payment Method</h4>
</div>

<div class="admin-card" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.payment-methods.store') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Method Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. PayPal" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Type Code <span class="text-danger">*</span></label>
                    <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" placeholder="e.g. paypal, manual, bkash" required>
                    <div class="form-text">Used programmatically to identify the method. No spaces.</div>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" placeholder="e.g. Pay securely via your PayPal account">
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Payment Instructions</label>
                    <textarea name="instructions" class="form-control @error('instructions') is-invalid @enderror" rows="4" placeholder="Enter bank details, mobile numbers, or specific instructions for this method">{{ old('instructions') }}</textarea>
                    <div class="form-text">Shown to the customer when they select this method at checkout.</div>
                    @error('instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActive" checked value="1">
                        <label class="form-check-label ms-2 fw-bold" for="isActive">Enable this payment method</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Payment Method</button>
            </div>
        </form>
    </div>
</div>
@endsection
