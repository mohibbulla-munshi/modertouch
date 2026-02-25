@extends('layouts.app')
@section('title', $category->name . ' - Shop')
@section('meta_description', $category->meta_description ?? 'Browse products in ' . $category->name)

@section('content')
<div class="container py-4">
    {{-- Top Filter Bar --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4" style="background:transparent;">
        <div class="d-flex align-items-center gap-2 mb-3 mb-md-0">
            @if($category->image)
                <img src="{{ asset('storage/'.$category->image) }}" alt="" style="width:32px; height:32px; object-fit:contain; border-radius:4px;">
            @else
                <i class="bi bi-box" style="color:var(--primary); font-size:1.5rem;"></i>
            @endif
            <h4 class="mb-0 fw-bold" style="color:#002c36; font-family:'Barlow', sans-serif;">{{ $category->name }}</h4>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2 flex-wrap" id="filterForm">
            <div class="d-flex align-items-center gap-2" style="background:#e2e8f0; border-radius:50px; padding:4px 12px;">
                <input type="number" name="min_price" class="form-control" placeholder="min price" value="{{ request('min_price') }}" style="width:90px; border:none; background:transparent; box-shadow:none; padding:4px; font-size:13px; text-align:center;">
                <input type="number" name="max_price" class="form-control" placeholder="Max price" value="{{ request('max_price') }}" style="width:90px; border:none; background:transparent; box-shadow:none; padding:4px; font-size:13px; text-align:center;">
            </div>
            
            <button type="submit" class="btn" style="background:#002c36; color:#fff; border-radius:50px; padding:6px 20px; font-size:13px; font-weight:600;">Filter</button>
            
            <select name="sort" class="form-select" style="width:120px; border-radius:50px; border:1px solid #cbd5e1; font-size:13px; padding:6px 30px 6px 16px; cursor:pointer;" onchange="this.form.submit()">
                <option value="newest" {{ request('sort','newest') === 'newest' ? 'selected' : '' }}>Sort</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High</option>
            </select>
        </form>
    </div>

    {{-- Product Grid --}}
    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size: 3rem; color: #cbd5e1;"></i>
            <h5 class="mt-3 text-muted">No products found in this category.</h5>
            <a href="{{ route('shop') }}" class="btn btn-outline-primary mt-2" style="border-radius:50px;">Browse All Products</a>
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
