@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="breadcrumb-section">
    <div class="container" style="max-width:1280px">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Wishlist</li>
        </ol>
    </div>
</div>

<section style="padding:32px 0 64px">
    <div class="container" style="max-width:1100px">
        <div class="row g-4">
            <div class="col-lg-3">@include('account._sidebar', ['active'=>'wishlist'])</div>

            <div class="col-lg-9">
                <h5 class="fw-700 mb-3" style="color:var(--primary)">
                    <i class="bi bi-heart me-2" style="color:var(--teal)"></i>My Wishlist
                    <span class="text-muted fw-400" style="font-size:.85rem">({{ $items->count() }} item{{ $items->count()!=1?'s':'' }})</span>
                </h5>

                @if($items->isEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-heart" style="font-size:2.5rem;color:var(--text-3)"></i>
                            <p class="mt-3 text-muted mb-3">Your wishlist is empty.</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-bag me-1"></i>Browse Products
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-xl-3">
                        @foreach($items as $item)
                            @php $product = $item->product; @endphp
                            @if(!$product) @continue @endif
                            @php
                                $displayPrice = $product->sale_price ?? $product->price;
                                $thumb = $product->featured_image
                                    ? asset('storage/'.$product->featured_image)
                                    : ($product->images->isNotEmpty()
                                        ? asset('storage/'.$product->images->first()->path)
                                        : null);
                            @endphp
                            <div class="col">
                                <div class="card border-0 shadow-sm h-100" style="border-radius:var(--radius-lg)">
                                    {{-- Image --}}
                                    <div style="position:relative;overflow:hidden;border-radius:var(--radius-lg) var(--radius-lg) 0 0">
                                        @if($thumb)
                                            <a href="{{ route('shop.product',$product->slug) }}">
                                                <img src="{{ $thumb }}" alt="{{ $product->name }}"
                                                     loading="lazy"
                                                     style="width:100%;height:160px;object-fit:cover;transition:transform .4s">
                                            </a>
                                        @else
                                            <div style="width:100%;height:160px;background:var(--surface-2);display:flex;align-items:center;justify-content:center;color:var(--text-3);font-size:2rem">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif

                                        {{-- Sale badge --}}
                                        @if($product->sale_price)
                                            @php $disc = round((1-$product->sale_price/$product->price)*100); @endphp
                                            <span style="position:absolute;top:8px;left:8px;background:#EF4444;color:#fff;font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:4px">-{{ $disc }}%</span>
                                        @endif

                                        {{-- Stock status --}}
                                        @if(!$product->is_active || $product->stock === 0)
                                            <div style="position:absolute;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center">
                                                <span style="color:#fff;font-size:.78rem;font-weight:700;background:rgba(0,0,0,.5);padding:4px 12px;border-radius:20px">Out of Stock</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Body --}}
                                    <div class="card-body p-3 d-flex flex-column">
                                        @if($product->sku)
                                            <div class="text-muted" style="font-size:.68rem;margin-bottom:2px">SKU: {{ $product->sku }}</div>
                                        @endif
                                        <a href="{{ route('shop.product',$product->slug) }}"
                                           class="fw-600 text-dark mb-1"
                                           style="font-size:.875rem;line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                            {{ $product->name }}
                                        </a>

                                        {{-- Price --}}
                                        <div class="d-flex align-items-baseline gap-2 mb-3">
                                            <span class="fw-800" style="color:var(--teal);font-size:1rem">৳{{ number_format($displayPrice,2) }}</span>
                                            @if($product->sale_price)
                                                <span class="text-muted text-decoration-line-through" style="font-size:.8rem">৳{{ number_format($product->price,2) }}</span>
                                            @endif
                                        </div>

                                        {{-- Stock indicator --}}
                                        @if($product->stock > 0 && $product->stock <= $product->low_stock_threshold)
                                            <div class="text-warning mb-2" style="font-size:.72rem;font-weight:600">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Only {{ $product->stock }} left
                                            </div>
                                        @endif

                                        <div class="mt-auto d-flex gap-2">
                                            @if($product->is_active && $product->stock > 0)
                                                <button onclick="addToCart({{ $product->id }})" class="btn btn-primary btn-sm flex-fill" style="font-size:.78rem">
                                                    <i class="bi bi-cart-plus me-1"></i>Add to Cart
                                                </button>
                                            @else
                                                <a href="{{ route('shop.product',$product->slug) }}" class="btn btn-outline-primary btn-sm flex-fill" style="font-size:.78rem">
                                                    View
                                                </a>
                                            @endif
                                            <form action="{{ route('account.wishlist.toggle',$product) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Remove from wishlist">
                                                    <i class="bi bi-heartbreak"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
