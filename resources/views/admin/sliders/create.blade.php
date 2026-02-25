@extends('layouts.admin')
@section('title', 'Add Slider')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.sliders.index') }}">Sliders</a></li>
<li class="breadcrumb-item active">Add Slider</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-image me-2" style="color:var(--teal)"></i>Add New Slider</h4>
        <div class="page-header-sub">Create a hero banner slide for the homepage</div>
    </div>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
<form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-type me-2" style="color:var(--teal)"></i>Slide Content</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Main Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Premium Industrial Furniture">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}" placeholder="Short supporting text...">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="button_text" class="form-control" value="{{ old('button_text') }}" placeholder="e.g. Shop Now">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="url" name="button_url" class="form-control" value="{{ old('button_url') }}" placeholder="https://...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-image me-2" style="color:var(--teal)"></i>Slider Image <span style="color:#EF4444">*</span></div>
                <div class="card-body">
                    <div id="imgPreview" class="mb-3"></div>
                    <div style="background:var(--surface-2);border:2px dashed var(--border);border-radius:var(--radius);padding:24px;text-align:center">
                        <i class="bi bi-cloud-upload" style="font-size:2rem;color:var(--text-3);display:block;margin-bottom:8px"></i>
                        <div style="font-size:.84rem;color:var(--text-2);margin-bottom:12px">Upload slider banner image (recommended: 1920×700px)</div>
                        <input type="file" name="image" id="sliderImg" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                        <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Lower = shown first</div>
                    </div>
                    <div class="mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-eye me-1" style="color:var(--teal)"></i>Active & Visible
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Save Slider
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@push('scripts')
<script>
document.getElementById('sliderImg')?.addEventListener('change', function () {
    const preview = document.getElementById('imgPreview');
    if (this.files[0]) {
        preview.innerHTML = `<img src="${URL.createObjectURL(this.files[0])}" style="width:100%;border-radius:8px;border:1.5px solid var(--border);max-height:200px;object-fit:cover">`;
    }
});
</script>
@endpush
@endsection
