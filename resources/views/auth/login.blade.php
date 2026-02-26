@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5" style="max-width: 440px; margin: 0 auto;">
    <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #0F1923, #1B3A5C);">
            <h4 class="text-white fw-bold mb-0">Welcome Back</h4>
            <p class="text-white-50 mb-0 small">Sign in to your account</p>
        </div>
        <div class="card-body p-4">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between">
                        Password
                        <a href="{{ route('password.request') }}" class="text-primary small">Forgot?</a>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="pwdField" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <span class="input-group-text bg-light border-start-0" style="cursor:pointer" onclick="togglePwd()"><i class="bi bi-eye" id="eyeIcon"></i></span>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-600" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>
        </div>
        <div class="card-footer text-center py-3 bg-light">
            <span class="text-muted small">Don't have an account? </span>
            <a href="{{ route('register') }}" class="text-primary fw-600 small">Create account</a>
        </div>
    </div>
</div>

<script>
function togglePwd() {
    const f = document.getElementById('pwdField');
    const i = document.getElementById('eyeIcon');
    f.type = f.type === 'password' ? 'text' : 'password';
    i.className = f.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

@if(session('reg_success'))
{{-- Popup removed as per request --}}
@endif
</script>
@endsection
