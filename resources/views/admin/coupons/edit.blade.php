@extends('layouts.admin')
@section('title', 'Edit Coupon')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-ticket-perforated me-2" style="color:var(--teal)"></i>Edit Coupon</h4>
        <div class="page-header-sub" style="font-family:monospace;font-weight:700;font-size:.95rem;color:var(--primary)">{{ $coupon->code }}</div>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
@if($errors->any())
<div class="alert alert-danger mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif
<form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-info-circle me-2" style="color:var(--teal)"></i>Coupon Details</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Coupon Code <span style="color:#EF4444">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase @error('code') is-invalid @enderror"
                                   value="{{ old('code', $coupon->code) }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Discount Type <span style="color:#EF4444">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (৳)</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description', $coupon->description) }}" placeholder="Internal note about this coupon">
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-sliders me-2" style="color:var(--teal)"></i>Discount Rules</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Discount Value <span style="color:#EF4444">*</span></label>
                            <input type="number" step="0.01" name="value"
                                   class="form-control @error('value') is-invalid @enderror"
                                   value="{{ old('value', (float)$coupon->value) }}" required>
                            @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min. Order (৳)</label>
                            <input type="number" step="0.01" name="minimum_order" class="form-control"
                                   value="{{ old('minimum_order', (float)$coupon->minimum_order) }}" placeholder="No minimum">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Max Discount (৳)</label>
                            <input type="number" step="0.01" name="maximum_discount" class="form-control"
                                   value="{{ old('maximum_discount', (float)$coupon->maximum_discount) }}" placeholder="No cap">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Usage Limit</label>
                            <input type="number" name="usage_limit" class="form-control"
                                   value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Unlimited">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Used Count</label>
                            <input type="text" class="form-control" value="{{ $coupon->used_count ?? 0 }}" readonly
                                   style="background:var(--surface-2);cursor:default">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expires At</label>
                            <input type="date" name="expires_at"
                                   class="form-control @error('expires_at') is-invalid @enderror"
                                   value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}">
                            @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card" style="border-color:var(--teal)">
                <div class="card-header" style="background:rgba(13,115,119,.08);border-bottom-color:rgba(13,115,119,.2)">
                    <span style="color:var(--teal)"><i class="bi bi-cloud-upload me-2"></i>Publish</span>
                </div>
                <div class="card-body">
                    <div class="mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                   {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-eye me-1" style="color:var(--teal)"></i>Active & Usable
                            </label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-1"></i>Update
                        </button>
                        <form id="del-coupon-edit" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('del-coupon-edit')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
