@extends('layouts.admin')
@section('title', 'Edit Slider')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.sliders.index') }}">Sliders</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-image me-2" style="color:var(--teal)"></i>Edit Slider</h4>
        <div class="page-header-sub">{{ $slider->title ?? 'Untitled Slider' }}</div>
    </div>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
<form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-type me-2" style="color:var(--teal)"></i>Slide Content</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Main Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $slider->subtitle) }}">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $slider->button_text) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="url" name="button_url" class="form-control" value="{{ old('button_url', $slider->button_url) }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-image me-2" style="color:var(--teal)"></i>Slider Image</div>
                <div class="card-body">
                    @if($slider->image)
                        <img src="{{ asset('storage/'.$slider->image) }}" id="currentImg"
                             style="width:100%;border-radius:8px;border:1.5px solid var(--border);max-height:200px;object-fit:cover;margin-bottom:14px">
                    @endif
                    <div id="imgPreview"></div>
                    <div style="background:var(--surface-2);border:2px dashed var(--border);border-radius:var(--radius);padding:16px;text-align:center">
                        <div style="font-size:.8rem;color:var(--text-2);margin-bottom:10px">Replace image (leave blank to keep current)</div>
                        <input type="file" name="image" id="sliderImg" class="form-control @error('image') is-invalid @enderror" accept="image/*">
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
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $slider->sort_order) }}">
                    </div>
                    <div class="mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-eye me-1" style="color:var(--teal)"></i>Active & Visible
                            </label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-1"></i>Update
                        </button>
                        <form id="del-slider" action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('del-slider')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@push('scripts')
<script>
document.getElementById('sliderImg')?.addEventListener('change', function () {
    const current = document.getElementById('currentImg');
    const preview = document.getElementById('imgPreview');
    if (this.files[0]) {
        const url = URL.createObjectURL(this.files[0]);
        if (current) { current.src = url; preview.innerHTML = ''; }
        else { preview.innerHTML = `<img src="${url}" style="width:100%;border-radius:8px;border:1.5px solid var(--border);max-height:200px;object-fit:cover;margin-bottom:12px">`; }
    }
});
</script>
@endpush
@endsection
