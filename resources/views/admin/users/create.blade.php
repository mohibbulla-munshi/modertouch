@extends('layouts.admin')
@section('title', 'Add Admin User')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
<li class="breadcrumb-item active">Add User</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-person-plus me-2" style="color:var(--teal)"></i>Add Admin User</h4>
        <div class="page-header-sub">Create a new admin panel account</div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
@if($errors->any())
<div class="alert alert-danger mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif
<div class="row g-4">
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="card-header"><i class="bi bi-person me-2" style="color:var(--teal)"></i>User Details</div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span style="color:#EF4444">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span style="color:#EF4444">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role <span style="color:#EF4444">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div style="border-top:1px solid var(--border);padding-top:20px;margin-top:20px">
                        <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-3);display:block;margin-bottom:14px">Password</label>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Password <span style="color:#EF4444">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span style="color:#EF4444">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Create User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
