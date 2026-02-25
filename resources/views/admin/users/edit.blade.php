@extends('layouts.admin')
@section('title', 'Edit Admin User')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-person-gear me-2" style="color:var(--teal)"></i>Edit User</h4>
        <div class="page-header-sub">{{ $user->name }}</div>
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
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span style="color:#EF4444">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span style="color:#EF4444">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Role <span style="color:#EF4444">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required {{ $user->hasRole('Super Admin') ? 'disabled' : '' }}>
                            @if($user->hasRole('Super Admin'))
                                <option value="Super Admin" selected>Super Admin</option>
                            @else
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    @if($role->name !== 'Super Admin')
                                        <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @if($user->hasRole('Super Admin'))
                            <input type="hidden" name="role" value="Super Admin">
                        @endif
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div style="border-top:1px solid var(--border);padding-top:20px;margin-top:4px">
                        <label style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-3);display:block;margin-bottom:14px">
                            Change Password <span style="font-weight:400;text-transform:none;letter-spacing:0">(leave blank to keep current)</span>
                        </label>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" minlength="8">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-1"></i>Update User
                        </button>
                        @if($user->id !== auth()->id())
                        <form id="del-user-edit" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('del-user-edit')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
