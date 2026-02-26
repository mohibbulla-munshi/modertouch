@extends('layouts.admin')
@section('title', 'Edit City')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.cities.index') }}">Cities</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h4 class="mb-1"><i class="bi bi-geo-alt me-2 text-teal"></i>Edit City / Shipping</h4>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-body">
                <form action="{{ route('admin.cities.update', $city->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">City Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $city->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shipping Cost (৳) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="shipping_cost" class="form-control @error('shipping_cost') is-invalid @enderror" value="{{ old('shipping_cost', $city->shipping_cost) }}" required min="0">
                        @error('shipping_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active (Visible in checkout)</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Update</button>
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-light ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
