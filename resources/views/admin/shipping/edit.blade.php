@extends('layouts.admin')
@section('title', 'Edit Shipping Zone')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.shipping.index') }}">Shipping</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-truck me-2" style="color:var(--teal)"></i>Edit Shipping Zone</h4>
        <div class="page-header-sub">{{ $shippingZone->name }}</div>
    </div>
    <a href="{{ route('admin.shipping.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
<form action="{{ route('admin.shipping.update', $shippingZone->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="admin-card" style="border-color:var(--teal)">
                <div class="card-header" style="background:rgba(13,115,119,.08);border-bottom-color:rgba(13,115,119,.2)">
                    <span style="color:var(--teal)"><i class="bi bi-geo-alt me-2"></i>Zone Details</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Zone Name <span style="color:#EF4444">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $shippingZone->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regions / Areas</label>
                        <textarea name="regions" class="form-control" rows="3">{{ old('regions', $shippingZone->regions) }}</textarea>
                    </div>
                    <div class="mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                   {{ old('is_active', $shippingZone->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-check-circle me-1" style="color:var(--teal)"></i>Zone Active
                            </label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-1"></i>Update Zone
                        </button>
                        <form id="del-zone-edit" action="{{ route('admin.shipping.destroy', $shippingZone->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('del-zone-edit')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-currency-exchange me-2" style="color:var(--teal)"></i>Shipping Rates</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>Rate Name</th><th>Cost</th><th>Free Over</th></tr>
                        </thead>
                        <tbody>
                            @forelse($shippingZone->rates as $rate)
                            <tr>
                                <td style="font-weight:600;font-size:.875rem">{{ $rate->name }}</td>
                                <td style="font-weight:700;color:var(--primary);font-family:'Inter',sans-serif">৳{{ number_format($rate->rate, 0) }}</td>
                                <td style="font-size:.84rem;color:var(--text-2)">
                                    {{ $rate->free_over ? '৳'.number_format($rate->free_over, 0) : '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4" style="color:var(--text-3)">
                                    <i class="bi bi-currency-exchange d-block mb-1" style="font-size:1.5rem;opacity:.4"></i>No rates defined yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="padding:14px 20px;border-top:1px solid var(--border)">
                    <div style="font-size:.78rem;color:var(--text-3)">
                        <i class="bi bi-info-circle me-1" style="color:var(--teal)"></i>
                        To manage individual rates, additional backend support is required.
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
