@extends('layouts.admin')
@section('title', 'Add Category')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
<li class="breadcrumb-item active">Add Category</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-folder-plus me-2" style="color:var(--teal)"></i>Add New Category</h4>
        <div class="page-header-sub">Create a new product category for your catalog</div>
    </div>
    <a href="{{ route('admin.categories.index') }}"
       class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back to Categories
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">

        {{-- Main --}}
        <div class="col-lg-8">

            {{-- Basic Info --}}
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-info-circle me-2" style="color:var(--teal)"></i>Category Details</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label">Category Name <span style="color:#EF4444">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Office Furniture" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">— None (Top Level) —</option>
                                @foreach($parents as $p)
                                    <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Brief description of this category...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-search me-2" style="color:var(--teal)"></i>SEO Settings</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control"
                                   value="{{ old('meta_title') }}" placeholder="Page title for search engines">
                            <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Leave blank to auto-use category name</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meta Description</label>
                            <input type="text" name="meta_description" class="form-control"
                                   value="{{ old('meta_description') }}" placeholder="Short description for search results">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">

            {{-- Publish --}}
            <div class="admin-card mb-4" style="border-color:var(--teal)">
                <div class="card-header" style="background:rgba(13,115,119,.08);border-bottom-color:rgba(13,115,119,.2)">
                    <span style="color:var(--teal)"><i class="bi bi-cloud-upload me-2"></i>Publish</span>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-eye me-1" style="color:var(--teal)"></i>Active & Visible
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                        <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Lower number = displayed first</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Save Category
                    </button>
                </div>
            </div>

            {{-- Image --}}
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-image me-2" style="color:var(--teal)"></i>Category Image</div>
                <div class="card-body">
                    <div id="imgPreview" class="mb-3"></div>
                    <div style="background:var(--surface-2);border:2px dashed var(--border);border-radius:var(--radius);padding:20px;text-align:center">
                        <i class="bi bi-cloud-upload" style="font-size:1.5rem;color:var(--text-3);display:block;margin-bottom:6px"></i>
                        <div style="font-size:.8rem;color:var(--text-2);margin-bottom:10px">Upload category image</div>
                        <input type="file" name="image" id="catImage"
                               class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('catImage')?.addEventListener('change', function () {
    const preview = document.getElementById('imgPreview');
    preview.innerHTML = '';
    if (this.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(this.files[0]);
        img.style.cssText = 'width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border)';
        preview.appendChild(img);
    }
});
</script>
@endpush
@endsection
