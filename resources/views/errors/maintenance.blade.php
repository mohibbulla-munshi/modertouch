@extends('layouts.app')
@section('title', 'Maintenance Mode')
@section('content')
<div class="container py-5 text-center" style="max-width:550px; min-height:60vh; display:flex; align-items:center; justify-content:center; flex-direction:column">
    <i class="bi bi-tools" style="font-size:4rem; color:#1B3A5C; display:block; margin-bottom:24px"></i>
    <h2 class="fw-bold mb-3" style="color:#1B3A5C">We're Under Maintenance</h2>
    <p class="text-muted">Our website is currently undergoing scheduled maintenance. We'll be back online shortly.</p>
    <p class="text-muted small">For urgent inquiries: <a href="tel:{{ \App\Models\Setting::getValue('phone') }}">{{ \App\Models\Setting::getValue('phone', '+880 1234-567890') }}</a></p>
</div>
@endsection
