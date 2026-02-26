@extends('layouts.admin')
@section('title', 'Edit Payment Method')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.payment-methods.index') }}">Payment Methods</a></li>
<li class="breadcrumb-item active">Edit {{ $paymentMethod->name }}</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h4 class="mb-1"><i class="bi bi-pencil-square me-2" style="color:var(--teal)"></i>Edit Payment Method</h4>
</div>

<div class="admin-card" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Method Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $paymentMethod->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Type Code <span class="text-danger">*</span></label>
                    <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $paymentMethod->type) }}" required>
                    <div class="form-text">Used programmatically to identify the method. No spaces.</div>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $paymentMethod->description) }}">
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Payment Instructions</label>
                    <textarea name="instructions" class="form-control @error('instructions') is-invalid @enderror" rows="4">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
                    <div class="form-text">Shown to the customer when they select this method at checkout.</div>
                    @error('instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActive" value="1" {{ $paymentMethod->is_active ? 'checked' : '' }}>
                        <label class="form-check-label ms-2 fw-bold" for="isActive">Enable this payment method</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Update Method</button>
            </div>
        </form>
    </div>
</div>
@endsection
