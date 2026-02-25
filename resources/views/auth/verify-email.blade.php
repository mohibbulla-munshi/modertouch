@extends('layouts.app')
@section('title', 'Verify Email')
@section('content')
<div class="container py-5" style="max-width: 500px; margin: 0 auto;">
    <div class="card border-0 shadow-lg text-center" style="border-radius: 16px; padding: 40px;">
        <i class="bi bi-envelope-check" style="font-size: 3rem; color: #1B3A5C;"></i>
        <h4 class="fw-bold mt-3 mb-2">Verify Your Email</h4>
        <p class="text-muted">We sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Check your inbox and click the link to activate your account.</p>
        <form action="{{ route('verification.send') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-arrow-clockwise me-1"></i>Resend Verification Email
            </button>
        </form>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf <button class="btn btn-link text-muted small">Not you? Sign out</button>
        </form>
    </div>
</div>
@endsection
