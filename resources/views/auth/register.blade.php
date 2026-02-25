@extends('layouts.app')
@section('title', 'Create Account')
@section('content')
<div class="container py-5" style="max-width: 500px; margin: 0 auto;">
    <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #0F1923, #1B3A5C);">
            <h4 class="text-white fw-bold mb-0">Create Account</h4>
            <p class="text-white-50 mb-0 small">Join Modern Touch BD today</p>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('register.post') }}" method="POST" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Enter your full name" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="you@example.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="+880 1700-000000">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min 8 characters" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600">
                            <i class="bi bi-person-plus-fill me-2"></i>Create Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-center py-3 bg-light">
            <span class="text-muted small">Already have an account? </span>
            <a href="{{ route('login') }}" class="text-primary fw-600 small">Sign in</a>
        </div>
    </div>
</div>
@endsection
