@extends('layouts.app')
@section('title', 'Search Results for "' . $query . '"')
@section('content')
<div class="breadcrumb-section">
    <div class="container"><nav><ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Search: "{{ $query }}"</li>
    </ol></nav></div>
</div>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Search Results</h5>
            <small class="text-muted">{{ $products->total() }} results for "<strong>{{ $query }}</strong>"</small>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-secondary">Browse All</a>
    </div>
    @if($products->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-search" style="font-size:3rem; color:#ccc"></i>
        <h5 class="mt-3 text-muted">No results found for "{{ $query }}"</h5>
        <p class="text-muted">Try different keywords or browse our <a href="{{ route('shop') }}">full catalogue</a>.</p>
    </div>
    @else
    <div class="row g-3">
        @foreach($products as $product)
            @include('partials.product-card', compact('product'))
        @endforeach
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
    @endif
</div>
@endsection
