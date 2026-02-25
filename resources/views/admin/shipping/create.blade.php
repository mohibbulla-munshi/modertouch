@extends('layouts.admin')
@section('title', 'Add Shipping Zone')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.shipping.index') }}">Shipping</a></li>
<li class="breadcrumb-item active">Add Zone</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-truck me-2" style="color:var(--teal)"></i>Add Shipping Zone</h4>
        <div class="page-header-sub">Define delivery area and rate tiers</div>
    </div>
    <a href="{{ route('admin.shipping.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
<form action="{{ route('admin.shipping.store') }}" method="POST">
    @csrf
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
                               value="{{ old('name') }}" placeholder="e.g. Inside Dhaka" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regions / Areas</label>
                        <textarea name="regions" class="form-control" rows="3" placeholder="Dhaka North, Dhaka South...">{{ old('regions') }}</textarea>
                        <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Comma-separated list of covered areas</div>
                    </div>
                    <div class="mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-check-circle me-1" style="color:var(--teal)"></i>Zone Active
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="admin-card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-currency-exchange me-2" style="color:var(--teal)"></i>Shipping Rates</span>
                    <button type="button" id="addRateBtn" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus me-1"></i>Add Rate
                    </button>
                </div>
                <div class="card-body">
                    <div id="ratesContainer">
                        <div class="rate-row mb-3 p-3 position-relative" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                            <button type="button" class="btn btn-sm btn-danger remove-rate position-absolute" style="top:8px;right:8px">
                                <i class="bi bi-x"></i>
                            </button>
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label">Rate Name <span style="color:#EF4444">*</span></label>
                                    <input type="text" name="rates[0][name]" class="form-control" placeholder="Standard Delivery" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Cost (৳) <span style="color:#EF4444">*</span></label>
                                    <input type="number" step="0.01" name="rates[0][rate]" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Free Over (৳)</label>
                                    <input type="number" step="0.01" name="rates[0][free_over]" class="form-control" placeholder="Optional">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-check-circle me-2"></i>Save Shipping Zone
            </button>
        </div>
    </div>
</form>
@push('scripts')
<script>
let rateIdx = 1;
document.getElementById('addRateBtn').addEventListener('click', function () {
    const container = document.getElementById('ratesContainer');
    const div = document.createElement('div');
    div.className = 'rate-row mb-3 p-3 position-relative';
    div.style = 'background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)';
    div.innerHTML = `
        <button type="button" class="btn btn-sm btn-danger remove-rate position-absolute" style="top:8px;right:8px"><i class="bi bi-x"></i></button>
        <div class="row g-2">
            <div class="col-12"><label class="form-label">Rate Name <span style="color:#EF4444">*</span></label>
            <input type="text" name="rates[${rateIdx}][name]" class="form-control" placeholder="e.g. Express" required></div>
            <div class="col-md-6"><label class="form-label">Cost (৳) <span style="color:#EF4444">*</span></label>
            <input type="number" step="0.01" name="rates[${rateIdx}][rate]" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Free Over (৳)</label>
            <input type="number" step="0.01" name="rates[${rateIdx}][free_over]" class="form-control" placeholder="Optional"></div>
        </div>`;
    container.appendChild(div);
    div.querySelector('.remove-rate').addEventListener('click', () => div.remove());
    rateIdx++;
});
document.querySelectorAll('.remove-rate').forEach(b => b.addEventListener('click', function () { this.closest('.rate-row').remove(); }));
</script>
@endpush
@endsection
