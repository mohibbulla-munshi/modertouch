@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="breadcrumb-section">
    <div class="container" style="max-width:1280px">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">My Account</li>
        </ol>
    </div>
</div>

<section style="padding:32px 0 64px">
    <div class="container" style="max-width:1100px">
        <div class="row g-4">

            {{-- ── Sidebar ────────────────────────────── --}}
            <div class="col-lg-3">
                @include('account._sidebar', ['active' => 'profile'])
            </div>

            {{-- ── Main ───────────────────────────────── --}}
            <div class="col-lg-9">

                {{-- Profile Card --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle" style="color:var(--teal)"></i>
                        Personal Information
                    </div>
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger mb-3 py-2">
                                <ul class="mb-0 ps-3 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif
                        <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row g-3">
                                {{-- Avatar preview --}}
                                <div class="col-12 d-flex align-items-center gap-3 mb-2">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="Avatar"
                                             style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                                    @else
                                        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:800;color:#fff;font-family:'Inter',sans-serif;flex-shrink:0">
                                            {{ strtoupper(substr($user->name,0,1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <label class="form-label mb-1">Profile Photo</label>
                                        <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                                        <div class="text-muted" style="font-size:.72rem;margin-top:3px">Max 1 MB · JPG, PNG, GIF</div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}" placeholder="+880...">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Account Role</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_',' ',$user->role ?? 'customer')) }}" disabled>
                                </div>
                                <div class="col-12 d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-lg me-1"></i> Save Changes
                                    </button>
                                    <a href="{{ route('account.orders') }}" class="btn btn-outline-primary px-4">My Orders</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Change Password Card --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock" style="color:var(--teal)"></i>
                        Change Password
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('account.password.update') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">New Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required minlength="8">
                                    <div class="text-muted" style="font-size:.72rem;margin-top:3px">Minimum 8 characters</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-lock me-1"></i> Update Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
