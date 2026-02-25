@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="container py-5" style="max-width: 440px; margin: 0 auto;">
    <div class="card border-0 shadow-lg" style="border-radius: 16px;">
        <div class="card-body p-5">
            <h4 class="fw-bold text-center mb-4" style="color:#1B3A5C">Set New Password</h4>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $email ?? '') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Min 8 characters">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
