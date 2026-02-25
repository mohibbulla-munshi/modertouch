@extends('layouts.app')

@section('title', 'Home')
@section('meta_description', 'Modern Touch BD — Premium Industrial Furniture, Steel Racking & Shelving Solutions in Bangladesh.')

@push('seo_extra')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Modern Touch BD",
  "url": "{{ url('/') }}",
  "telephone": "{{ \App\Models\Setting::getValue('phone', '+880 1700-000000') }}"
}
</script>
@endpush

@push('styles')
<style>
/* ── Top-Category carousel ─────────────────────────── */
.top-cat-wrap{overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding-bottom:4px;}
.top-cat-wrap::-webkit-scrollbar{display:none;}
.top-cat-inner{display:flex;gap:10px;width:max-content;}
.top-cat-item{display:flex;flex-direction:column;align-items:center;gap:6px;text-decoration:none;flex-shrink:0;width:90px;}
.top-cat-thumb{width:76px;height:76px;border-radius:50%;border:2px solid #F97316;overflow:hidden;background:#f5f5f5;display:flex;align-items:center;justify-content:center;}
.top-cat-thumb img{width:100%;height:100%;object-fit:cover;}
.top-cat-item span{font-size:11px;font-weight:600;color:rgb(0,44,54);text-align:center;line-height:14px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.top-cat-item small{font-size:10px;color:#F97316;font-weight:700;}

/* ── Section heading ──────────────────────────────── */
.sec-head{display:flex;align-items:center;gap:8px;margin-bottom:14px;}
.sec-head-title{font-family:'Barlow',sans-serif;font-size:16px;font-weight:800;color:rgb(0,44,54);margin:0;}
.sec-head-more{margin-left:auto;font-size:12px;font-weight:600;color:rgb(215,42,78);text-decoration:none;white-space:nowrap;}
.sec-head-more:hover{text-decoration:underline;}

/* ── Shipping banner ──────────────────────────────── */
.ship-banner{background:#FFF8F0;border:1px solid #FFE0C2;border-radius:8px;padding:12px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:13px;font-weight:600;color:rgb(0,44,54);}
.ship-banner a{background:rgb(0,44,54);color:#fff;border-radius:6px;padding:7px 18px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;}

/* ── Product grid ─────────────────────────────────── */
.products-section{padding:16px 0;}

/* ── Hero Swiper Desktop Height ────────────────────── */
.hero-swiper { height: 400px; }
  @media(max-width:991px){ .hero-swiper { height:360px; } }

.hero-overlay { position: absolute; inset: 0; display: flex; align-items: center; background: linear-gradient(to right, rgba(0,22,28,0.85) 0%, rgba(0,44,54,0.4) 100%); z-index: 10; }
</style>
@endpush

@section('content')

{{-- ═══ HERO SLIDER ════════════════════════════════════════════════ --}}
@if($sliders->isNotEmpty())
<div class="swiper hero-swiper">
    <div class="swiper-wrapper">
        @foreach($sliders as $slide)
        <div class="swiper-slide" style="position:relative;overflow:hidden">
            <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title ?? 'Slider Image' }}" style="width:100%;height:100%;object-fit:cover">
            @if($slide->title || $slide->subtitle || $slide->button_text)
            <div class="hero-overlay">
                <div style="max-width:1280px;margin:0 auto;padding:0 24px">
                    @if($slide->subtitle)
                    <p style="font-family:'Barlow',sans-serif;font-size:16px;font-weight:600;color:var(--gold);margin-bottom:12px;text-transform:uppercase;letter-spacing:1px">{{ $slide->subtitle }}</p>
                    @endif
                    
                    @if($slide->title)
                    <h1 class="hero-content" style="font-family:'Barlow',sans-serif;font-size:3rem;font-weight:900;color:#fff;margin-bottom:20px;line-height:1.1;max-width:700px">{{ $slide->title }}</h1>
                    @endif
                    
                    @if($slide->button_text)
                    <div style="display:flex;gap:12px;flex-wrap:wrap">
                        <a href="{{ $slide->button_url ?? route('shop') }}" class="btn-primary-custom" style="padding:12px 28px;font-size:15px;background:var(--gold);color:#000;border:none;">{{ $slide->button_text }} <i class="bi bi-arrow-right"></i></a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
@else
{{-- Default hero --}}
<div style="background:linear-gradient(105deg,rgb(0,22,28) 0%,rgb(0,44,54) 50%,rgb(0,88,107) 100%);height:380px;display:flex;align-items:center;position:relative;overflow:hidden;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px">
        <div class="hero-eyebrow">Bangladesh's Trusted Industrial Partner</div>
        <h1 style="font-family:'Barlow',sans-serif;font-size:2.8rem;font-weight:900;color:#fff;line-height:1.1;margin-bottom:16px">
            Industrial Furniture &<br><span style="color:var(--gold)">Racking Solutions</span>
        </h1>
        <p style="font-family:'Barlow',sans-serif;font-size:15px;color:rgba(255,255,255,.82);margin-bottom:24px;max-width:460px">
            Premium quality steel furniture, racking systems, and industrial shelving tailored for your business.
        </p>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a href="{{ route('shop') }}" class="btn-primary-custom">Shop Now <i class="bi bi-arrow-right"></i></a>
            <a href="{{ route('contact') }}" class="btn-outline-white">Get a Quote</a>
        </div>
    </div>
</div>
@endif

<div style="padding:14px 16px 0;max-width:100%">

{{-- ═══ SHIPPING BANNER ════════════════════════════════════════════ --}}
<div class="ship-banner mb-4">
    <span>🚚 Looking for Bulk / Shipping Service?</span>
    <a href="{{ route('contact') }}">Contact Us →</a>
</div>

{{-- ═══ TOP CATEGORIES (horizontal scroll) ═══════════════════════ --}}
@if($categories->isNotEmpty())
<section class="mb-4">
    <div class="sec-head">
        <span style="font-size:18px">⚡</span>
        <h2 class="sec-head-title">Top Category</h2>
        <a href="{{ route('shop') }}" class="sec-head-more">View All →</a>
    </div>
    <div class="top-cat-wrap">
        <div class="top-cat-inner">
            @foreach($categories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}" class="top-cat-item">
                <div class="top-cat-thumb">
                    @if($cat->image)
                        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                    @else
                        <i class="bi bi-grid" style="font-size:1.6rem;color:rgb(0,44,54);opacity:.5"></i>
                    @endif
                </div>
                <small>{{ $cat->products_count ?? 0 }} products</small>
                <span>{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ FEATURED PRODUCTS ══════════════════════════════════════════ --}}
@if($featuredProducts->isNotEmpty())
<section class="products-section mb-4">
    <div class="sec-head">
        <span style="font-size:18px">⭐</span>
        <h2 class="sec-head-title">Featured Products</h2>
        <a href="{{ route('shop', ['sort' => 'featured']) }}" class="sec-head-more">View All →</a>
    </div>
    <div class="row g-2">
        @foreach($featuredProducts as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif


{{-- ═══ NEW ARRIVALS ═══════════════════════════════════════════════ --}}
@if($newProducts->isNotEmpty())
<section class="products-section mb-4">
    <div class="sec-head">
        <span style="font-size:18px">💗</span>
        <h2 class="sec-head-title">Trending Products</h2>
        <a href="{{ route('shop', ['sort' => 'newest']) }}" class="sec-head-more">View All →</a>
    </div>
    <div class="row g-2">
        @foreach($newProducts as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

{{-- ═══ PROMO BANNER ═══════════════════════════════════════════════ --}}
<section class="mb-4">
    <div class="promo-banner">
        <div class="row align-items-center" style="position:relative;z-index:1">
            <div class="col-lg-8">
                <div style="font-size:.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--gold);margin-bottom:10px">Special Pricing Available</div>
                <h3 style="font-family:'Barlow',sans-serif;font-size:1.7rem;font-weight:900;color:#fff;margin-bottom:8px">Looking for Bulk Orders?</h3>
                <p style="color:rgba(255,255,255,.75);font-size:14px;margin:0">We offer special pricing for large quantity orders. Contact us for custom quotes.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('contact') }}" class="btn-primary-custom" style="background:#fff;color:var(--primary)">Get a Custom Quote <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

</div>{{-- /padding wrapper --}}

@endsection

@push('scripts')
<script>
// Hero Swiper
new Swiper('.hero-swiper', {
    loop: true, speed: 700,
    autoplay: { delay: 5500, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    effect: 'fade', fadeEffect: { crossFade: true },
});

// Mobile search toggle
document.getElementById('mobileSearchToggle')?.addEventListener('click', function() {
    const bar = document.getElementById('mobileSearchBar');
    bar.style.display = bar.style.display === 'none' ? 'block' : 'none';
});

// Add bottom padding for mobile sticky nav
if (window.innerWidth < 992) {
    document.body.classList.add('has-mobile-nav');
}
</script>
@endpush
