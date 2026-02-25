@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="container py-5" style="max-width: 440px; margin: 0 auto;">
    <div class="card border-0 shadow-lg" style="border-radius: 16px;">
        <div class="card-body p-5">
            <h4 class="fw-bold text-center mb-2" style="color:#1B3A5C">Forgot Password?</h4>
            <p class="text-muted text-center small mb-4">Enter your email and we'll send a reset link.</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600">
                    <i class="bi bi-send me-2"></i>Send Reset Link
                </button>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Back to login</a>
            </div>
        </div>
    </div>
</div>
@endsection
