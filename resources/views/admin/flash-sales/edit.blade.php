@extends('layouts.admin')
@section('title', 'Edit Flash Sale - ' . $flashSale->name)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.flash-sales.index') }}">Flash Sales</a></li>
<li class="breadcrumb-item active">Edit Campaign</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4>Manage Campaign: {{ $flashSale->name }}</h4>
        <div class="page-header-sub">Update campaign details and manage participating products.</div>
    </div>
    @php $isOpen = $flashSale->isOpen(); @endphp
    <div class="d-flex align-items-center gap-2">
        <span class="badge {{ $isOpen ? 'bg-success' : ($flashSale->start_time > now() ? 'bg-primary' : 'bg-secondary') }} py-2 px-3 fw-bold" style="font-size: .85rem">
            Status: {{ $isOpen ? 'LIVE' : ($flashSale->start_time > now() ? 'UPCOMING' : 'ENDED') }}
        </span>
    </div>
</div>

<div class="row g-4">
    {{-- campaign Settings --}}
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="card-header">Campaign Settings</div>
            <div class="card-body">
                <form action="{{ route('admin.flash-sales.update', $flashSale) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $flashSale->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control" value="{{ $flashSale->start_time->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control" value="{{ $flashSale->end_time->format('Y-m-d\TH:i') }}" required>
                    </div>
                    @if($flashSale->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$flashSale->image) }}" class="img-fluid rounded border p-1" alt="">
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Replace Banner</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $flashSale->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Update Settings</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Participating Products --}}
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box-seam me-2" style="color:var(--teal)"></i>Add Participating Product</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.flash-sales.add-product', $flashSale) }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-5">
                        <label class="form-label">Search Product</label>
                        <select name="product_id" id="productPicker" class="form-select" required>
                            <option value=""></option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}" data-price="{{ $prod->price }}">
                                    {{ $prod->name }} (Normal Price: ৳{{ number_format($prod->price, 0) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Flash Sale Price (৳)</label>
                        <input type="number" name="sale_price" step="0.01" class="form-control" id="salePrice" placeholder="0.00" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sale Limit</label>
                        <input type="number" name="quantity_limit" class="form-control" placeholder="10" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-teal w-100">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header">Current Deals</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Original Price</th>
                            <th>Flash Price</th>
                            <th>Limit / Sold</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flashSale->products as $fp)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $fp->product->featured_image ? asset('storage/'.$fp->product->featured_image) : 'https://via.placeholder.com/40' }}" style="width: 32px; height: 32px; object-fit: cover;" class="rounded">
                                    <span class="fw-600">{{ $fp->product->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted">৳ {{ number_format($fp->product->price, 0) }}</td>
                            <td><span class="text-danger fw-700">৳ {{ number_format($fp->price, 0) }}</span></td>
                            <td>
                                <div class="progress" style="height: 6px; width: 80px;">
                                    @php $percent = $fp->quantity_limit > 0 ? ($fp->sold_quantity / $fp->quantity_limit) * 100 : 0; @endphp
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, $percent) }}%"></div>
                                </div>
                                <div class="small mt-1 text-muted">{{ $fp->sold_quantity }} / {{ $fp->quantity_limit }} sold</div>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.flash-sales.remove-product', $fp) }}" method="POST" onsubmit="return confirm('Remove product from sale?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove">
                                        <i class="bi bi-x-circle-fill fs-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No products added for this campaign yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#productPicker').select2({
        theme: 'bootstrap-5',
        placeholder: "Search for a product...",
        allowClear: true
    });

    $('#productPicker').on('change', function() {
        const option = $(this).find(':selected');
        const price = option.data('price');
        if (price) {
            // Suggest a 20% discount by default
            $('#salePrice').val((price * 0.8).toFixed(2));
        }
    });
});
</script>
@endpush
