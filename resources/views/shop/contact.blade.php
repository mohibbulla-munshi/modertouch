@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')
<div class="breadcrumb-section">
    <div class="container"><nav><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active">Contact</li></ol></nav></div>
</div>
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <h2 class="section-title mb-4">Get In Touch</h2>
            <p class="text-muted mb-4">Have a question about our industrial furniture or racking systems? We're here to help. Send us a message and we'll respond within 24 hours.</p>

            <div class="d-flex gap-3 mb-3">
                <div style="width:44px; height:44px; background:rgba(26,58,107,.1); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0">
                    <i class="bi bi-geo-alt-fill" style="color:#1B3A5C"></i>
                </div>
                <div>
                    <div class="fw-600" style="font-weight:600">Address</div>
                    <div class="text-muted small">{{ \App\Models\Setting::getValue('address', 'Dhaka, Bangladesh') }}</div>
                </div>
            </div>
            <div class="d-flex gap-3 mb-3">
                <div style="width:44px; height:44px; background:rgba(26,58,107,.1); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0">
                    <i class="bi bi-telephone-fill" style="color:#1B3A5C"></i>
                </div>
                <div>
                    <div class="fw-600" style="font-weight:600">Phone</div>
                    <a href="tel:{{ \App\Models\Setting::getValue('phone') }}" class="text-muted small text-decoration-none">{{ \App\Models\Setting::getValue('phone', '+880 1234-567890') }}</a>
                </div>
            </div>
            <div class="d-flex gap-3 mb-4">
                <div style="width:44px; height:44px; background:rgba(26,58,107,.1); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0">
                    <i class="bi bi-envelope-fill" style="color:#1B3A5C"></i>
                </div>
                <div>
                    <div class="fw-600" style="font-weight:600">Email</div>
                    <a href="mailto:{{ \App\Models\Setting::getValue('email') }}" class="text-muted small text-decoration-none">{{ \App\Models\Setting::getValue('email', 'info@moderntouchbd.com') }}</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg" style="border-radius:16px">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Send us a Message</h5>
                    <form action="{{ route('contact.send') }}" method="POST" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
