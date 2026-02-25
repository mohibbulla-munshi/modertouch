<div class="col-6 col-md-4 col-xl-3">
    <a href="{{ route('shop.product', $product->slug) }}" class="product-card-link d-block text-decoration-none">
    <div class="product-card h-100 d-flex flex-column">
        {{-- Image Area --}}
        <div class="product-card-img" style="position:relative;overflow:hidden;background:#f7f7f7;">
            @php
                $img    = $product->images->first() ?? null;
                $imgSrc = $img
                    ? asset('storage/' . $img->image_path)
                    : ($product->featured_image
                        ? asset('storage/' . $product->featured_image)
                        : asset('images/no-image.png'));
                $discPct = $product->discount_percent ?? 0;
                $isNew   = $product->created_at->gt(now()->subDays(14));
            @endphp

            {{-- OFF Badge --}}
            @if($discPct > 0)
            <span style="position:absolute;top:8px;left:8px;z-index:2;background:rgb(215,42,78);color:#fff;font-family:'Barlow',sans-serif;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;letter-spacing:.3px;">
                {{ $discPct }}% OFF
            </span>
            @elseif($isNew)
            <span style="position:absolute;top:8px;left:8px;z-index:2;background:rgb(0,44,54);color:#fff;font-family:'Barlow',sans-serif;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;letter-spacing:.3px;">
                NEW
            </span>
            @endif

            {{-- Wishlist quick action --}}
            @auth
            <button class="quick-action-btn" style="position:absolute;top:8px;right:8px;z-index:2;opacity:0;transition:opacity .2s"
                    onclick="event.preventDefault();toggleWishlist({{ $product->id }}, this)" title="Wishlist">
                <i class="bi bi-heart{{ auth()->user()->wishlist()->where('product_id', $product->id)->exists() ? '-fill' : '' }}"
                   style="{{ auth()->user()->wishlist()->where('product_id', $product->id)->exists() ? 'color:#EF4444' : '' }}"></i>
            </button>
            @endauth

            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" loading="lazy"
                 style="width:100%;height:190px;object-fit:cover;display:block;transition:transform .4s;">
        </div>

        {{-- Card Body --}}
        <div class="product-card-body flex-grow-1 d-flex flex-column" style="padding:10px 12px 12px;">

            {{-- Title --}}
            <div style="font-size:13px;font-weight:500;color:rgb(0,44,54);line-height:18px;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:36px;">
                {{ Str::limit($product->name, 55) }}
            </div>

            {{-- Star Ratings --}}
            <div style="display:flex;align-items:center;gap:2px;margin-bottom:6px;">
                @php $rating = $product->average_rating > 0 ? $product->average_rating : 4; @endphp
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $rating ? '-fill' : ($i - $rating < 1 ? '-half' : '') }}"
                       style="font-size:11px;color:#F4C430;"></i>
                @endfor
            </div>

            {{-- Price + SOLD --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;">
                <div>
                    <span style="font-family:'Barlow',sans-serif;font-size:15px;font-weight:800;color:rgb(0,44,54);">৳ {{ number_format($product->current_price, 0) }}</span>
                    @if($product->sale_price && $product->sale_price < $product->price)
                    <span style="font-size:11px;color:#9CA3AF;text-decoration:line-through;margin-left:4px;">৳ {{ number_format($product->price, 0) }}</span>
                    @endif
                </div>
                <span style="font-size:11px;color:#9CA3AF;white-space:nowrap;">SOLD: {{ $product->sold_count ?? 0 }}</span>
            </div>
        </div>
    </div>
    </a>
</div>

@once
<style>
.product-card-link:hover .product-card { box-shadow: 0 6px 24px rgba(0,44,54,.12); }
.product-card-link:hover img { transform: scale(1.05); }
.product-card-link:hover .quick-action-btn { opacity: 1 !important; }
</style>
<script>
function toggleWishlist(productId, btn) {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch(`/account/wishlist/${productId}`, {
        method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => {
        const icon = btn.querySelector('i');
        icon.className = d.in_wishlist ? 'bi bi-heart-fill' : 'bi bi-heart';
        icon.style.color = d.in_wishlist ? '#EF4444' : '';
    });
}
</script>
@endonce
