@extends('layouts.admin')
@section('title', 'General Settings')
@section('breadcrumb')
<li class="breadcrumb-item active">Settings</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-gear me-2" style="color:var(--teal)"></i>General Settings</h4>
        <div class="page-header-sub">Configure your store information and preferences</div>
    </div>
</div>
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-shop me-2" style="color:var(--teal)"></i>Store Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Store / App Name</label>
                            <input type="text" name="app_name" class="form-control" value="{{ App\Models\Setting::getValue('app_name', 'Modern Touch BD') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ App\Models\Setting::getValue('contact_email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ App\Models\Setting::getValue('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" name="currency" class="form-control" value="{{ App\Models\Setting::getValue('currency', '৳') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Store Address</label>
                            <textarea name="address" class="form-control" rows="3">{{ App\Models\Setting::getValue('address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-search me-2" style="color:var(--teal)"></i>SEO & Meta</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ App\Models\Setting::getValue('meta_title') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meta Description</label>
                            <input type="text" name="meta_description" class="form-control" value="{{ App\Models\Setting::getValue('meta_description') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card mb-4" style="border-color:var(--teal)">
                <div class="card-header" style="background:rgba(13,115,119,.08);border-bottom-color:rgba(13,115,119,.2)">
                    <span style="color:var(--teal)"><i class="bi bi-cloud-upload me-2"></i>Save</span>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Save Settings
                    </button>
                </div>
            </div>
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-image me-2" style="color:var(--teal)"></i>Store Logo</div>
                <div class="card-body">
                    @if(App\Models\Setting::getValue('logo'))
                        <img src="{{ asset('storage/'.App\Models\Setting::getValue('logo')) }}"
                             style="max-height:80px;border-radius:8px;border:1.5px solid var(--border);margin-bottom:12px" alt="Logo">
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Recommended: PNG with transparent background</div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
