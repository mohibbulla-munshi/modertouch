@extends('layouts.admin')
@section('title', 'Edit Tag')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-tag me-2" style="color:var(--teal)"></i>Edit Tag</h4>
        <div class="page-header-sub">{{ $tag->name }}</div>
    </div>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header"><i class="bi bi-tag me-2" style="color:var(--teal)"></i>Tag Details</div>
            <div class="card-body">
                <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Tag Name <span style="color:#EF4444">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $tag->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4" style="background:var(--surface-2);border-radius:var(--radius);padding:12px 14px;border:1px solid var(--border)">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-3)">Current Slug</div>
                        <div style="font-family:monospace;font-size:.84rem;color:var(--text-2)">{{ $tag->slug }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Update Tag
                        </button>
                        <form id="del-tag-edit" action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('del-tag-edit')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
