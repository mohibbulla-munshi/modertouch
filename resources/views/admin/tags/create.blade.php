@extends('layouts.admin')
@section('title', 'Add Tag')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
<li class="breadcrumb-item active">Add Tag</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-tag me-2" style="color:var(--teal)"></i>Add New Tag</h4>
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
                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Tag Name <span style="color:#EF4444">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Heavy Duty" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Slug will be auto-generated from the tag name</div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Tag
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
