@extends('layouts.admin')
@section('title', 'Create Flash Sale')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.flash-sales.index') }}">Flash Sales</a></li>
<li class="breadcrumb-item active">New Campaign</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4>Create New Flash Sale</h4>
        <div class="page-header-sub">Set the duration and branding for your next big sale.</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">Basic Information</div>
            <div class="card-body">
                <form action="{{ route('admin.flash-sales.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Campaign Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Summer Bonanza, Eid Mega Sale" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label">Start Time</label>
                            <input type="datetime-local" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                            @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">End Time</label>
                            <input type="datetime-local" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Campaign Banner (1200x400 recommended)</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Campaign is Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 py-2">
                        Create & Add Products <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
