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
            <form id="registerForm" action="{{ route('register.post') }}" method="POST" novalidate>
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
                    <div class="col-12 mt-4">
                        <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600" disabled>
                            <span id="btnText"><i class="bi bi-person-plus-fill me-2"></i>Create Account</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    const inputs = {
        name: form.querySelector('input[name="name"]'),
        email: form.querySelector('input[name="email"]'),
        phone: form.querySelector('input[name="phone"]'),
        password: form.querySelector('input[name="password"]'),
        confirm: form.querySelector('input[name="password_confirmation"]')
    };

    // Regex constants
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^(\+88|88)?01[3-9]\d{8}$/;

    function validateForm(showErrors = true) {
        let isValid = true;

        // Clean UI errors
        Object.values(inputs).forEach(input => {
            input.classList.remove('is-invalid');
            const feedback = input.parentNode.querySelector('.js-feedback');
            if (feedback) feedback.remove();
        });

        // Name
        if (inputs.name.value.trim().length < 1) {
            isValid = false;
            if (showErrors) showError(inputs.name, 'Name is required.');
        } else if (inputs.name.value.trim().length < 2) {
            isValid = false;
            if (showErrors) showError(inputs.name, 'Name must be at least 2 characters.');
        }

        // Email
        const emailVal = inputs.email.value.trim();
        if (emailVal === '') {
            isValid = false;
            if (showErrors) showError(inputs.email, 'Email address is required.');
        } else if (!emailRegex.test(emailVal)) {
            isValid = false;
            if (showErrors) showError(inputs.email, 'Please enter a valid email address.');
        }

        // Phone (Optional, but if present must be valid)
        const phoneVal = inputs.phone.value.trim();
        if (phoneVal !== '') {
            if (!phoneRegex.test(phoneVal)) {
                isValid = false;
                if (showErrors) showError(inputs.phone, 'Must be a valid Bangladeshi number.');
            }
        }

        // Password
        if (inputs.password.value.length < 1) {
            isValid = false;
            if (showErrors) showError(inputs.password, 'Password is required.');
        } else if (inputs.password.value.length < 8) {
            isValid = false;
            if (showErrors) showError(inputs.password, 'Password must be at least 8 characters.');
        }

        // Confirm Password
        if (inputs.confirm.value === '') {
            isValid = false;
            if (showErrors && inputs.password.value.length >= 1) {
                showError(inputs.confirm, 'Please confirm your password.');
            }
        } else if (inputs.password.value !== inputs.confirm.value) {
            isValid = false;
            if (showErrors) showError(inputs.confirm, 'Passwords do not match.');
        }

        return isValid;
    }

    function showError(input, message) {
        input.classList.add('is-invalid');
        const err = document.createElement('div');
        err.className = 'invalid-feedback js-feedback d-block'; // d-block ensures visibility
        err.innerText = message;
        input.parentNode.appendChild(err);
    }

    // Attach real-time validation (passing false to showErrors to avoid premature errors)
    Object.values(inputs).forEach(input => {
        input.addEventListener('input', () => validateForm(false));
        input.addEventListener('blur', () => validateForm(true));
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        // Run validation with showErrors = true
        const isFormValid = validateForm(true);
        
        if (!isFormValid) {
            e.preventDefault();
            const firstError = form.querySelector('.is-invalid');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Lock button only on valid submission
        submitBtn.disabled = true;
        btnText.innerText = "Processing...";
        btnSpinner.classList.remove('d-none');
    });

    // Enable button initially
    submitBtn.disabled = false;
});
</script>
@endpush
