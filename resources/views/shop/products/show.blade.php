@extends('layouts.app')

@section('title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)

@push('seo_extra')
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"Product",
  "name":"{{ $product->name }}",
  "description":"{{ strip_tags($product->short_description) }}",
  "sku":"{{ $product->sku }}",
  "brand":{"@type":"Brand","name":"Modern Touch BD"},
  "offers":{
    "@type":"Offer",
    "price":"{{ $product->current_price }}",
    "priceCurrency":"BDT",
    "availability":"{{ $product->is_in_stock ? 'InStock' : 'OutOfStock' }}",
    "url":"{{ url()->current() }}"
  }
  @if($product->average_rating > 0)
  ,"aggregateRating":{
    "@type":"AggregateRating",
    "ratingValue":"{{ $product->average_rating }}",
    "reviewCount":"{{ $product->reviews->count() }}"
  }
  @endif
}
</script>
@endpush

@section('content')
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
                @if($product->category)<li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>@endif
                <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        {{-- Image Gallery --}}
        <div class="col-lg-5">
            @php $mainImgSrc = $product->images->first()?->image_path ? asset('storage/' . $product->images->first()->image_path) : ($product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/no-image.png')); @endphp
            <div class="mb-3" style="border-radius: 12px; overflow: hidden; border: 1px solid #e0e0e0;">
                <img id="mainProductImg" src="{{ $mainImgSrc }}" alt="{{ $product->name }}" class="w-100" style="height: 420px; object-fit: cover;">
            </div>
            @if($product->images->count() > 1)
            <div class="d-flex gap-2 flex-wrap">
                @foreach($product->images as $img)
                <img src="{{ asset('storage/' . $img->image_path) }}" alt="" class="thumb-img"
                     onclick="document.getElementById('mainProductImg').src = this.src"
                     style="width:70px; height:70px; object-fit:cover; border-radius:8px; cursor:pointer; border: 2px solid transparent; transition:.2s;"
                     onmouseover="this.style.borderColor='#1B3A5C'" onmouseout="this.style.borderColor='transparent'">
                @endforeach
            </div>
            @endif
        </div>

        {{-- Product Details --}}
        <div class="col-lg-7">
            @if($product->category)<small class="text-muted">{{ $product->category->name }}</small>@endif
            <h1 class="h3 fw-bold mt-1 mb-2" style="color:#1B3A5C">{{ $product->name }}</h1>

            {{-- Rating --}}
            @if($product->reviews->isNotEmpty())
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $product->average_rating ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <span class="text-muted small">({{ $product->reviews->count() }} reviews)</span>
            </div>
            @endif

            {{-- Price --}}
            <div class="mb-3">
                <span id="displayPrice" class="display-6 fw-bold" style="color:#1B3A5C">৳ {{ number_format($product->current_price, 0) }}</span>
                @if($product->discount_percent)
                    <span class="ms-2 text-muted text-decoration-line-through fs-5">৳ {{ number_format($product->price, 0) }}</span>
                    <span class="ms-2 badge bg-danger" id="discountBadge">{{ $product->discount_percent }}% OFF</span>
                @endif
            </div>

            @if($product->short_description)<p class="text-muted">{{ $product->short_description }}</p>@endif

            {{-- Stock --}}
            <div id="stockContainer">
                @if($product->is_in_stock)
                    <span id="stockStatus" class="badge bg-success-subtle text-success mb-3"><i class="bi bi-check-circle me-1"></i>In Stock ({{ $product->stock }} available)</span>
                    @if($product->is_low_stock)
                        <span id="lowStockWarning" class="badge bg-warning-subtle text-warning mb-3 ms-2"><i class="bi bi-exclamation-triangle me-1"></i>Low Stock!</span>
                    @endif
                @else
                    <span id="stockStatus" class="badge bg-danger-subtle text-danger mb-3"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
                @endif
            </div>

            {{-- Variants --}}
            @if($product->variants->isNotEmpty())
            <div class="mb-3">
                <label class="form-label fw-600" style="font-weight:600">Select Variant</label>
                <div class="d-flex flex-wrap gap-2" id="variantButtons">
                    @foreach($product->variants->where('is_active', true) as $v)
                    <button type="button" class="btn btn-outline-primary variant-btn" 
                            data-id="{{ $v->id }}" 
                            data-price="{{ $v->price ?? $product->current_price }}" 
                            data-stock="{{ $v->stock }}">
                        {{ $v->name }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" id="variantSelect" value="">
            </div>
            @endif

            {{-- Quantity + Actions --}}
            <div class="d-flex align-items-center gap-3 mt-3 flex-wrap" id="actionButtons" style="{{ !$product->is_in_stock ? 'display:none !important' : '' }}">
                <div class="input-group" style="width: 130px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                    <input type="number" id="qtyInput" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                </div>
                <button class="btn btn-primary btn-lg px-4 cart-btn" style="background:#1B3A5C; border-color:#1B3A5C; border-radius:8px; font-weight:600"
                        onclick="addToCart({{ $product->id }}, document.getElementById('variantSelect')?.value || null, parseInt(document.getElementById('qtyInput').value))">
                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                </button>
                @auth
                <button class="btn btn-outline-danger btn-lg" onclick="toggleWishlist({{ $product->id }}, this)">
                    <i class="bi bi-heart{{ $isInWishlist ? '-fill' : '' }}"></i>
                </button>
                @endauth
                <a href="{{ route('checkout.index') }}" class="btn btn-warning btn-lg fw-600 px-4 buy-now-btn" style="border-radius:8px; font-weight:600">
                    Buy Now <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            {{-- SKU + Tags --}}
            <div class="mt-4 pt-3 border-top">
                @if($product->sku)<small class="text-muted">SKU: <strong>{{ $product->sku }}</strong> &nbsp;|&nbsp;</small>@endif
                @if($product->weight)<small class="text-muted">Weight: <strong>{{ $product->weight }} kg</strong> &nbsp;|&nbsp;</small>@endif
                @if($product->dimensions)<small class="text-muted">Dimensions: <strong>{{ $product->dimensions }}</strong></small>@endif
                @if($product->tags->isNotEmpty())
                <div class="mt-2">
                    @foreach($product->tags as $tag)
                    <a href="{{ route('shop', ['tag' => $tag->slug]) }}" class="badge bg-light text-dark text-decoration-none me-1" style="border: 1px solid #ddd">{{ $tag->name }}</a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-5">
        <ul class="nav nav-tabs mb-4" id="productTabs">
            <li class="nav-item"><button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#descPane" style="color:#1B3A5C; font-weight:600">Description</button></li>
            @if($product->specifications)<li class="nav-item"><button class="nav-link" id="spec-tab" data-bs-toggle="tab" data-bs-target="#specPane" style="color:#1B3A5C; font-weight:600">Specifications</button></li>@endif
            
            @foreach($product->tabs as $tab)
            <li class="nav-item"><button class="nav-link" id="custom-tab-{{ $tab->id }}" data-bs-toggle="tab" data-bs-target="#customPane-{{ $tab->id }}" style="color:#1B3A5C; font-weight:600">{{ $tab->heading }}</button></li>
            @endforeach

            <li class="nav-item"><button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#reviewPane" style="color:#1B3A5C; font-weight:600">Reviews ({{ $product->reviews->count() }})</button></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="descPane">
                <div class="product-description" style="max-width:800px; line-height:1.8">
                    {!! $product->description !!}
                </div>
            </div>
            @if($product->specifications)
            <div class="tab-pane fade" id="specPane">
                <div class="table-responsive" style="max-width:600px">
                    <table class="table table-bordered table-striped">
                        @foreach($product->specifications as $key => $value)
                        <tr><td class="fw-600 w-40" style="font-weight:600; background:#f8f9fa">{{ $key }}</td><td>{{ $value }}</td></tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif

            @foreach($product->tabs as $tab)
            <div class="tab-pane fade" id="customPane-{{ $tab->id }}">
                <div class="product-description" style="max-width:800px; line-height:1.8">
                    {!! $tab->content !!}
                </div>
            </div>
            @endforeach
            <div class="tab-pane fade" id="reviewPane">
                @if($product->reviews->isEmpty())
                <p class="text-muted">No reviews yet.</p>
                @else
                @foreach($product->reviews as $review)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong>{{ $review->user->name }}</strong>
                        <div class="stars" style="font-size:.85rem">
                            @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor
                        </div>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    @if($review->title)<p class="fw-600 mb-1">{{ $review->title }}</p>@endif
                    <p class="mb-0 text-muted">{{ $review->body }}</p>
                </div>
                @endforeach
                @endif

                @auth
                <div class="mt-4">
                    <h6 class="fw-bold">Write a Review</h6>
                    <form action="{{ route('review.store', $product->slug) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="d-flex gap-2">
                                @for($i=1;$i<=5;$i++)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="r{{ $i }}" value="{{ $i }}" required>
                                    <label class="form-check-label" for="r{{ $i }}">{{ $i }} ⭐</label>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3"><input type="text" name="title" class="form-control" placeholder="Review title (optional)"></div>
                        <div class="mb-3"><textarea name="body" class="form-control" rows="3" placeholder="Your review (optional)"></textarea></div>
                        <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->isNotEmpty())
    <div class="mt-5">
        <h2 class="section-title mb-4">Related Products</h2>
        <div class="row g-3">
            @foreach($related as $p)
                @include('partials.product-card', ['product' => $p])
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function changeQty(delta) {
    const el = document.getElementById('qtyInput');
    const max = parseInt(el.max) || 1;
    const val = parseInt(el.value) + delta;
    el.value = Math.max(1, Math.min(max, val));
}
function toggleWishlist(id, btn) {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(`/account/wishlist/${id}`, { method:'POST', headers:{'X-CSRF-TOKEN': csrf,'Accept':'application/json'} })
    .then(r => r.json()).then(d => {
        const i = btn.querySelector('i');
        i.className = d.in_wishlist ? 'bi bi-heart-fill' : 'bi bi-heart';
        Swal.fire({icon:'success',title:d.message,toast:true,position:'top-end',showConfirmButton:false,timer:2000});
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const variantBtns = document.querySelectorAll('.variant-btn');
    const variantInput = document.getElementById('variantSelect');
    const basePrice = {{ $product->current_price }};
    const baseStock = {{ $product->stock }};

    variantBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Unselect if already selected
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                variantInput.value = '';
                updateUI(basePrice, baseStock);
                return;
            }

            variantBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            variantInput.value = this.getAttribute('data-id');
            const vPrice = parseFloat(this.getAttribute('data-price'));
            const vStock = parseInt(this.getAttribute('data-stock'));
            updateUI(vPrice, vStock);
        });
    });

    function updateUI(price, stock) {
        const pDisplay = document.getElementById('displayPrice');
        const qInput = document.getElementById('qtyInput');
        const sStatus = document.getElementById('stockStatus');
        const warning = document.getElementById('lowStockWarning');
        const actions = document.getElementById('actionButtons');

        pDisplay.innerText = '৳ ' + new Intl.NumberFormat('en-IN').format(price);
        qInput.max = stock > 0 ? stock : 1;
        if (parseInt(qInput.value) > stock && stock > 0) {
            qInput.value = stock;
        } else if (stock === 0) {
            qInput.value = 1;
        }

        if (stock > 0) {
            sStatus.innerHTML = `<i class="bi bi-check-circle me-1"></i>In Stock (${stock} available)`;
            sStatus.className = "badge bg-success-subtle text-success mb-3";
            actions.style.cssText = '';
            if (warning) {
                warning.style.display = stock <= 5 ? 'inline-block' : 'none';
            }
        } else {
            sStatus.innerHTML = `<i class="bi bi-x-circle me-1"></i>Out of Stock`;
            sStatus.className = "badge bg-danger-subtle text-danger mb-3";
            actions.style.cssText = 'display:none !important';
            if (warning) warning.style.display = 'none';
        }
    }
});
</script>
<style>
.variant-btn {
    border: 1.5px solid #E2E6EE;
    color: #4B5563;
    background: #fff;
    transition: all 0.2s;
}
.variant-btn:hover {
    border-color: #1B3A5C;
    color: #1B3A5C;
    background: rgba(27, 58, 92, 0.05);
}
.variant-btn.active {
    border-color: #1B3A5C;
    color: #fff;
    background: #1B3A5C;
}
</style>
@endsection
