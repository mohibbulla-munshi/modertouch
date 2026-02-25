@extends('layouts.admin')
@section('title', 'Edit Category')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-folder2 me-2" style="color:var(--teal)"></i>Edit Category</h4>
        <div class="page-header-sub">{{ $category->name }}</div>
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

{{-- Stats bar --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="admin-card">
            <div class="card-body d-flex align-items-center gap-3" style="padding:16px 18px">
                <div class="stat-icon" style="background:rgba(13,115,119,.1)">
                    <i class="bi bi-box-seam" style="color:var(--teal)"></i>
                </div>
                <div>
                    <div class="stat-label">Products</div>
                    <div class="stat-value" style="font-size:1.2rem">{{ $category->products()->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-card">
            <div class="card-body d-flex align-items-center gap-3" style="padding:16px 18px">
                <div class="stat-icon" style="background:rgba(240,165,0,.1)">
                    <i class="bi bi-calendar3" style="color:var(--gold)"></i>
                </div>
                <div>
                    <div class="stat-label">Created</div>
                    <div style="font-weight:600;font-size:.84rem">{{ $category->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-card">
            <div class="card-body d-flex align-items-center gap-3" style="padding:16px 18px">
                <div class="stat-icon" style="background:rgba(139,92,246,.1)">
                    <i class="bi bi-link-45deg" style="color:#8B5CF6"></i>
                </div>
                <div>
                    <div class="stat-label">URL Slug</div>
                    <div style="font-weight:600;font-size:.78rem;font-family:monospace;color:var(--text-2)">{{ $category->slug }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">— None (Top Level) —</option>
                                @foreach($parents as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('parent_id', $category->parent_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
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
                                   value="{{ old('meta_title', $category->meta_title) }}"
                                   placeholder="Leave blank to auto-use name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meta Description</label>
                            <input type="text" name="meta_description" class="form-control"
                                   value="{{ old('meta_description', $category->meta_description) }}"
                                   placeholder="Short description for search results">
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
                                   id="isActive" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-eye me-1" style="color:var(--teal)"></i>Active & Visible
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $category->sort_order) }}">
                        <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Lower number = displayed first</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-1"></i>Update
                        </button>
                        <form id="del-cat" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('del-cat')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Image --}}
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-image me-2" style="color:var(--teal)"></i>Category Image</div>
                <div class="card-body">
                    @if($category->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $category->image) }}" id="currentImg"
                             style="width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border)">
                    </div>
                    @endif
                    <div id="imgPreview"></div>
                    <div style="background:var(--surface-2);border:2px dashed var(--border);border-radius:var(--radius);padding:14px;text-align:center">
                        <label class="form-label mb-2" style="font-size:.8rem;color:var(--text-2)">
                            {{ $category->image ? 'Replace Image' : 'Upload Image' }}
                        </label>
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
    const current = document.getElementById('currentImg');
    if (this.files[0]) {
        const url = URL.createObjectURL(this.files[0]);
        if (current) { current.src = url; preview.innerHTML = ''; }
        else {
            preview.innerHTML = `<img src="${url}" style="width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border);margin-bottom:12px">`;
        }
    }
});
</script>
@endpush
@endsection
