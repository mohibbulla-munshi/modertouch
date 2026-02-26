<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamic SEO --}}
    <title>@yield('title', \App\Models\Setting::getValue('app_name', 'Modern Touch')) - Modern Touch</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::getValue('tagline', 'Premium Industrial Furniture & Racking Solutions in Bangladesh.'))">
    <meta name="keywords" content="@yield('meta_keywords', 'industrial furniture, steel racking, office desks, bangladesh')">
    <meta name="author" content="Modern Touch">
    @yield('seo_extra')

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', \App\Models\Setting::getValue('app_name', 'Modern Touch'))">
    <meta property="og:site_name" content="Modern Touch">
    <meta property="og:description" content="@yield('meta_description', 'Premium Industrial Furniture & Racking in Bangladesh')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('storage/' . (\App\Models\Setting::getValue('favicon') ?? 'images/favicon.ico')) }}">

    {{-- Bootstrap 5 CSS CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts: Barlow --}}
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <style>
        :root {
            --primary:      rgb(0, 44, 54);
            --primary-dark: rgb(0, 30, 38);
            --primary-mid:  rgb(0, 66, 80);
            --teal:         rgb(0, 44, 54);
            --teal-light:   rgb(0, 88, 107);
            --gold:         #F0A500;
            --dark:         rgb(0, 22, 28);
            --header-bg:    #ffffff;
            --body-bg:      #F5F7F8;
            --surface:      #FFFFFF;
            --surface-2:    #EEF1F2;
            --border:       #DDE2E4;
            --text:         rgb(0, 44, 54);
            --text-2:       #3D5A60;
            --text-3:       #7A9599;
            --radius:       8px;
            --radius-lg:    12px;
            --shadow-sm:    0 1px 3px rgba(0,44,54,.07);
            --shadow:       0 4px 16px rgba(0,44,54,.10);
            --shadow-lg:    0 20px 60px rgba(0,44,54,.13);
            --tr:           .22s cubic-bezier(.4,0,.2,1);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        html{scroll-behavior:smooth;}
        body{font-family:'Barlow',sans-serif;background:var(--body-bg);color:var(--text);font-size:14px;line-height:20px;font-weight:500;-webkit-font-smoothing:antialiased;overflow-x:hidden;}
        h1,h2,h3,h4,h5,h6{font-family:'Barlow',sans-serif;font-weight:700;line-height:1.25;color:var(--primary);}
        a{color:var(--primary);text-decoration:none;transition:color var(--tr);}
        a:hover{color:var(--teal-light);}
        img{max-width:100%;display:block;}

        /* Progress bar */
        #page-progress{position:fixed;top:0;left:0;z-index:10000;height:3px;width:0%;background:linear-gradient(90deg,var(--teal),var(--gold));transition:width .1s linear;border-radius:0 2px 2px 0;}

        /* Reveal animations — gated on .js-ready so no-JS=content visible */
        .js-ready .reveal{opacity:0;transform:translateY(28px);transition:opacity .6s ease,transform .6s ease;}
        .js-ready .reveal.revealed{opacity:1;transform:none;}
        .reveal-delay-1{transition-delay:.1s;}.reveal-delay-2{transition-delay:.2s;}.reveal-delay-3{transition-delay:.3s;}.reveal-delay-4{transition-delay:.4s;}
        /* fade-in on page load */
        @keyframes pageFadeIn{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:none;}}
        .page-load-anim{animation:pageFadeIn .6s ease forwards;}

        /* Navbar */
        .navbar-main{background:var(--header-bg);padding:0;border-bottom:2px solid var(--primary);box-shadow:0 2px 8px rgba(0,44,54,.09);position:sticky;top:0;z-index:1030;transition:box-shadow var(--tr);}
        .navbar-main.scrolled{box-shadow:0 4px 20px rgba(0,44,54,.14);}
        .navbar-main .container{height:60px;display:flex;align-items:center;gap:16px;max-width:1280px;}
        .navbar-brand{font-family:'Barlow',sans-serif;font-size:1.35rem;font-weight:900;color:var(--primary)!important;letter-spacing:-.3px;white-space:nowrap;flex-shrink:0;}
        .navbar-brand .brand-accent{color:var(--teal-light);}
        .search-form{flex:1;max-width:380px;display:flex;background:var(--surface-2);border:1.5px solid var(--border);border-radius:6px;overflow:hidden;transition:border-color var(--tr),box-shadow var(--tr);}
        .search-form:focus-within{border-color:var(--primary);box-shadow:0 0 0 3px rgba(0,44,54,.10);background:#fff;}
        .search-form .form-control{border:none!important;background:transparent!important;padding:0 14px;font-size:13px;font-family:'Barlow',sans-serif;font-weight:500;color:var(--text);box-shadow:none!important;height:38px;}
        .search-form .btn{background:var(--primary);border:none;color:#fff;padding:0 16px;border-radius:0;height:38px;display:flex;align-items:center;transition:background var(--tr);}
        .search-form .btn:hover{background:var(--primary-dark);}
        .nav-links{display:flex;align-items:center;gap:0;margin-left:auto;list-style:none;padding:0;}
        .nav-links .nav-link{font-family:'Barlow',sans-serif;font-size:14px;font-weight:600;color:var(--text)!important;padding:8px 14px!important;border-radius:0;transition:all var(--tr);position:relative;letter-spacing:.3px;}
        .nav-links .nav-link::after{content:'';position:absolute;bottom:0;left:14px;right:14px;height:2px;background:var(--primary);transform:scaleX(0);transition:transform var(--tr);border-radius:0;}
        .nav-links .nav-link:hover{color:var(--primary)!important;background:var(--surface-2);}
        .nav-links .nav-link:hover::after,.nav-links .nav-link.active::after{transform:scaleX(1);}
        .nav-links .nav-link.active{color:var(--primary)!important;font-weight:700;}
        .nav-icon-btn{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:6px;color:var(--text);transition:all var(--tr);position:relative;text-decoration:none;}
        .nav-icon-btn:hover{background:var(--surface-2);color:var(--primary);}
        .cart-badge{position:absolute;top:4px;right:4px;background:var(--primary);color:#fff;font-size:.58rem;font-weight:700;min-width:16px;height:16px;border-radius:10px;display:flex;align-items:center;justify-content:center;padding:0 4px;}
        .btn-admin{background:var(--primary);color:#fff!important;font-family:'Barlow',sans-serif;font-size:13px;font-weight:600;padding:6px 16px;border-radius:5px;letter-spacing:.3px;transition:all var(--tr);text-decoration:none;}
        .btn-admin:hover{background:var(--primary-dark);transform:translateY(-1px);}
        .navbar-toggler{border:1.5px solid var(--border);border-radius:6px;padding:5px 9px;color:var(--text);background:none;font-size:1.1rem;cursor:pointer;}

        /* Section Titles */
        .section-title{font-family:'Barlow',sans-serif;font-size:1.65rem;font-weight:800;color:var(--primary);letter-spacing:-.3px;position:relative;padding-bottom:12px;}
        .section-title::after{content:'';position:absolute;left:0;bottom:0;width:32px;height:3px;background:var(--primary);border-radius:2px;transition:width .4s;}
        .section-title:hover::after{width:64px;}
        .section-title.center{text-align:center;}
        .section-title.center::after{left:50%;transform:translateX(-50%);}
        .section-subtitle{color:var(--text-2);font-size:13px;margin-top:6px;font-weight:500;}

        /* Product Cards */
        .product-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:transform var(--tr),box-shadow var(--tr),border-color var(--tr);position:relative;}
        .product-card:hover{transform:translateY(-6px);box-shadow:var(--shadow-lg);border-color:var(--primary);}
        .product-card-img{position:relative;overflow:hidden;background:var(--surface-2);}
        .product-card-img img{height:210px;object-fit:cover;width:100%;transition:transform .55s cubic-bezier(.4,0,.2,1);}
        .product-card:hover img{transform:scale(1.06);}
        .product-card-img .badges{position:absolute;top:10px;left:10px;display:flex;flex-direction:column;gap:4px;}
        .badge-new{background:var(--primary);color:#fff;font-size:.6rem;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:.5px;text-transform:uppercase;}
        .badge-sale{background:#E53935;color:#fff;font-size:.6rem;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:.5px;text-transform:uppercase;}
        .product-card-img .quick-actions{position:absolute;top:10px;right:10px;display:flex;flex-direction:column;gap:6px;opacity:0;transform:translateX(10px);transition:all var(--tr);}
        .product-card:hover .quick-actions{opacity:1;transform:none;}
        .quick-action-btn{width:32px;height:32px;background:rgba(255,255,255,.92);backdrop-filter:blur(8px);border:none;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--text-2);cursor:pointer;font-size:.85rem;transition:all var(--tr);box-shadow:0 2px 8px rgba(0,0,0,.1);}
        .quick-action-btn:hover{background:var(--primary);color:#fff;}
        .product-card-body{padding:12px 14px;}
        .product-card-category{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-3);margin-bottom:4px;}
        .product-card-title{font-size:13px;font-weight:600;color:var(--primary);line-height:18px;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
        .product-card-price{display:flex;align-items:baseline;gap:8px;}
        .price-current{font-family:'Barlow',sans-serif;font-size:1.05rem;font-weight:800;color:var(--primary);}
        .price-old{font-size:.82rem;color:var(--text-3);text-decoration:line-through;}
        .btn-add-cart{width:100%;border:none;border-radius:0 0 var(--radius) var(--radius);background:var(--primary);color:#fff;font-family:'Barlow',sans-serif;font-weight:600;font-size:13px;padding:10px;cursor:pointer;transition:background var(--tr),letter-spacing var(--tr);letter-spacing:.2px;}
        .btn-add-cart:hover{background:var(--primary-mid);letter-spacing:.6px;}

        /* Category Cards */
        .category-card{border-radius:var(--radius);overflow:hidden;position:relative;cursor:pointer;transition:transform var(--tr),box-shadow var(--tr);background:var(--primary);}
        .category-card:hover{transform:translateY(-5px) scale(1.02);box-shadow:0 16px 40px rgba(0,44,54,.22);}
        .category-card img{height:160px;object-fit:cover;width:100%;transition:transform .5s,opacity .3s;}
        .category-card:hover img{transform:scale(1.07);opacity:.82;}
        .category-card .overlay{position:absolute;inset:0;background:linear-gradient(0deg,rgba(0,22,28,.85) 0%,rgba(0,22,28,.15) 60%,transparent 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:14px;transition:background var(--tr);}
        .category-card:hover .overlay{background:linear-gradient(0deg,rgba(0,44,54,.85) 0%,rgba(0,44,54,.3) 60%,transparent 100%);}
        .category-card .overlay h6{margin:0;font-family:'Barlow',sans-serif;font-weight:700;font-size:.9rem;color:#fff;}
        .category-card .overlay small{color:rgba(255,255,255,.6);font-size:.68rem;}

        /* Chaldal Cart Action Badge */
        .chaldal-cart-action {
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            width: 120px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            opacity: 0;
            transition: all var(--tr);
        }
        .product-card:hover .chaldal-cart-action {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .chaldal-cart-action.has-items {
            opacity: 1 !important; /* Always show if active */
        }
        .chaldal-cart-action .btn-chaldal-add {
            width: 100%;
            border: 1px solid var(--primary);
            background: rgba(255,255,255,0.95);
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
            padding: 5px 0;
            cursor: pointer;
            transition: all var(--tr);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border-radius: 20px;
        }
        .chaldal-cart-action .btn-chaldal-add:hover {
            background: var(--primary);
            color: #fff;
        }
        .chaldal-cart-action .chaldal-qty-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--primary);
            color: #fff;
            width: 100%;
            border-radius: 20px;
        }
        .chaldal-qty-controls button {
            border: none;
            background: transparent;
            color: #fff;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chaldal-qty-controls button:hover {
            background: rgba(255,255,255,0.2);
        }
        .chaldal-qty-controls .chaldal-qty-val {
            font-size: 13px;
            font-weight: 700;
        }

        /* Breadcrumb */
        .breadcrumb-section{background:var(--surface);padding:12px 0;border-bottom:1px solid var(--border);}
        .breadcrumb-item a{color:var(--teal);font-size:.85rem;font-weight:500;}
        .breadcrumb-item.active{color:var(--text-2);font-size:.85rem;}
        .breadcrumb-item+.breadcrumb-item::before{color:var(--text-3);}

        /* Buttons */
        .btn{font-family:'Barlow',sans-serif;font-weight:600;font-size:14px;border-radius:var(--radius);transition:all var(--tr);letter-spacing:.2px;}
        .btn:active{transform:scale(.97);}
        .btn-primary,.btn-primary:focus{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff!important;box-shadow:0 4px 12px rgba(0,44,54,.3)!important;}
        .btn-primary:hover{background:var(--primary-mid)!important;border-color:var(--primary-mid)!important;transform:translateY(-1px);}
        .btn-outline-primary{border-color:var(--primary)!important;color:var(--primary)!important;background:transparent!important;}
        .btn-outline-primary:hover{background:var(--primary)!important;color:#fff!important;transform:translateY(-1px);}
        .btn-dark{background:var(--dark)!important;border-color:var(--dark)!important;color:#fff!important;}
        .btn-dark:hover{background:var(--primary-dark)!important;transform:translateY(-1px);}
        .btn-warning{background:var(--gold)!important;border-color:var(--gold)!important;color:#fff!important;}
        .btn-warning:hover{filter:brightness(1.1);transform:translateY(-1px);}
        .btn-primary-custom{background:var(--primary);color:#fff;border:none;padding:12px 32px;border-radius:6px;font-family:'Barlow',sans-serif;font-weight:700;font-size:14px;letter-spacing:.4px;transition:all var(--tr);box-shadow:0 4px 16px rgba(0,44,54,.35);display:inline-flex;align-items:center;gap:8px;}
        .btn-primary-custom:hover{background:var(--primary-mid);color:#fff;transform:translateY(-2px);box-shadow:0 10px 28px rgba(0,44,54,.3);}
        .btn-outline-white{border:2px solid rgba(255,255,255,.5);color:#fff;background:transparent;padding:10px 28px;border-radius:6px;font-family:'Barlow',sans-serif;font-weight:600;font-size:14px;transition:all var(--tr);}
        .btn-outline-white:hover{background:rgba(255,255,255,.15);border-color:#fff;color:#fff;}
        .btn-outline-light{border-color:rgba(255,255,255,.4)!important;color:#fff!important;border-radius:6px!important;}
        .btn-outline-light:hover{background:rgba(255,255,255,.12)!important;}

        /* Hero */
        .hero-swiper{height:520px;}
        .hero-swiper .swiper-slide{position:relative;overflow:hidden;}
        .hero-swiper img{width:100%;height:100%;object-fit:cover;}
        .hero-overlay{position:absolute;inset:0;background:linear-gradient(105deg,rgba(0,22,28,.92) 0%,rgba(0,44,54,.68) 45%,rgba(0,88,107,.15) 100%);display:flex;align-items:center;}
        .hero-content{max-width:600px;}
        .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;font-family:'Barlow',sans-serif;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:2.5px;color:var(--gold);margin-bottom:14px;}
        .hero-eyebrow::before{content:'';display:block;width:20px;height:2px;background:var(--gold);border-radius:2px;}
        .hero-content h1{font-family:'Barlow',sans-serif;font-size:3.4rem;font-weight:900;line-height:1.05;color:#fff;letter-spacing:-.5px;margin-bottom:16px;}
        .hero-content h1 span{color:var(--gold);}
        .hero-content p{font-family:'Barlow',sans-serif;font-size:15px;font-weight:500;color:rgba(255,255,255,.82);margin-bottom:28px;max-width:480px;line-height:22px;}
        .hero-cta{display:flex;gap:12px;flex-wrap:wrap;}
        .swiper-button-next,.swiper-button-prev{color:#fff!important;background:rgba(0,44,54,.35);backdrop-filter:blur(8px);border-radius:4px;width:44px!important;height:44px!important;border:1px solid rgba(255,255,255,.2);transition:all var(--tr);}
        .swiper-button-next:hover,.swiper-button-prev:hover{background:var(--primary);border-color:var(--primary);}
        .swiper-button-next::after,.swiper-button-prev::after{font-size:1rem!important;}
        .swiper-pagination-bullet{background:rgba(255,255,255,.5)!important;width:8px!important;height:8px!important;}
        .swiper-pagination-bullet-active{background:var(--gold)!important;width:24px!important;border-radius:4px;transition:width .3s;}

        /* Promo Banner */
        .promo-banner{background:linear-gradient(135deg,var(--primary) 0%,#0D4A7D 40%,var(--teal) 100%);border-radius:var(--radius-lg);padding:56px 48px;position:relative;overflow:hidden;}
        .promo-banner::before{content:'';position:absolute;right:-60px;top:-60px;width:280px;height:280px;background:rgba(255,255,255,.05);border-radius:50%;}
        .promo-banner::after{content:'';position:absolute;right:60px;bottom:-80px;width:200px;height:200px;background:rgba(255,255,255,.04);border-radius:50%;}

        /* Trust Badges */
        .trust-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;}
        .trust-item{display:flex;align-items:center;gap:12px;padding:18px 20px;}
        .trust-icon{width:44px;height:44px;border-radius:8px;background:rgba(0,44,54,.08);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:var(--primary);flex-shrink:0;}
        .trust-item h6{font-family:'Barlow',sans-serif;font-size:13px;font-weight:700;color:var(--primary);margin:0 0 2px;}
        .trust-item small{color:var(--text-2);font-size:12px;}

        /* Forms */
        .form-control,.form-select{border:1.5px solid var(--border);border-radius:var(--radius);color:var(--text);font-size:.9rem;padding:10px 14px;transition:border-color var(--tr),box-shadow var(--tr);background:#fff;}
        .form-control:focus,.form-select:focus{border-color:var(--teal);box-shadow:0 0 0 3px rgba(13,115,119,.12);outline:none;}
        .form-label{font-weight:600;font-size:.84rem;color:var(--text);margin-bottom:6px;}
        .input-group-text{background:var(--surface-2);border-color:var(--border);color:var(--text-2);}
        .form-check-input:checked{background-color:var(--teal);border-color:var(--teal);}

        /* Cards */
        .card{border:1.5px solid var(--border);border-radius:var(--radius-lg);background:var(--surface);box-shadow:var(--shadow-sm);}
        .card-header{background:var(--surface-2);border-bottom:1px solid var(--border);font-weight:700;color:var(--primary);font-family:'Inter',sans-serif;padding:14px 20px;font-size:.9rem;border-radius:var(--radius-lg) var(--radius-lg) 0 0!important;letter-spacing:.1px;}

        /* Badges */
        .stars{color:var(--gold);}
        .badge.bg-primary{background:var(--primary)!important;}

        /* Flash Messages */
        .flash-messages{position:fixed;top:76px;right:20px;z-index:9998;min-width:300px;max-width:400px;}
        .flash-messages .alert{border-radius:var(--radius);border:none;box-shadow:var(--shadow-lg);animation:slideInRight .35s cubic-bezier(.4,0,.2,1);}
        @keyframes slideInRight{from{opacity:0;transform:translateX(24px);}to{opacity:1;transform:none;}}

        /* Scroll Top */
        #scroll-top{position:fixed;bottom:24px;right:24px;width:44px;height:44px;background:var(--teal);color:#fff;border:none;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(13,115,119,.4);cursor:pointer;z-index:500;opacity:0;transform:translateY(16px);transition:all var(--tr);font-size:1.1rem;}
        #scroll-top.visible{opacity:1;transform:none;}
        #scroll-top:hover{background:var(--primary);transform:translateY(-2px);}

        /* Footer */
        footer{background:var(--primary-dark);color:rgba(255,255,255,.55);padding:60px 0 0;margin-top:80px;border-top:3px solid var(--primary);}
        .footer-brand{font-family:'Barlow',sans-serif;font-size:1.35rem;font-weight:900;color:#fff;letter-spacing:-.3px;}
        .footer-brand span{color:var(--gold);}
        .footer-desc{font-size:.875rem;line-height:1.8;color:rgba(255,255,255,.5);margin-top:10px;margin-bottom:20px;}
        .footer-social{display:flex;gap:10px;}
        .footer-social a{width:38px;height:38px;border-radius:50%;border:1px solid rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.5);font-size:1rem;transition:all var(--tr);text-decoration:none;}
        .footer-social a:hover{background:var(--teal);border-color:var(--teal);color:#fff;transform:translateY(-3px);}
        footer h6{color:#fff;font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:1.8px;margin-bottom:18px;font-family:'Inter',sans-serif;}
        footer a{color:rgba(255,255,255,.75);font-size:.875rem;transition:color var(--tr);text-decoration:none;}
        footer a:hover{color:var(--gold);}
        footer p{color:rgba(255,255,255,.75);font-size:.875rem;}
        .footer-bottom{margin-top:48px;border-top:1px solid rgba(255,255,255,.06);padding:22px 0;}
        .footer-bottom p{font-size:.82rem;color:rgba(255,255,255,.25);margin:0;}
        .footer-newsletter .input-group{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:50px;overflow:hidden;}
        .footer-newsletter .form-control{background:transparent!important;border:none;color:#fff;font-size:.875rem;padding:12px 18px;box-shadow:none;}
        .footer-newsletter .form-control::placeholder{color:rgba(255,255,255,.35);}
        .footer-newsletter .btn{background:var(--teal);color:#fff;border:none;border-radius:0 50px 50px 0;padding:0 22px;font-size:1rem;}
        .footer-newsletter .btn:hover{background:var(--gold);}

        .text-teal{color:var(--primary)!important;}.text-gold{color:var(--gold)!important;}
        .fw-500{font-weight:500!important;}.fw-600{font-weight:600!important;}.fw-700{font-weight:700!important;}.fw-800{font-weight:800!important;}
        .section-pad{padding:72px 0;}.section-pad-sm{padding:44px 0;}
        a.text-primary,.text-primary{color:var(--primary)!important;}
        hr{border-color:var(--border);opacity:1;}

        /* Card header */
        .card{border:1px solid var(--border);border-radius:var(--radius);background:var(--surface);box-shadow:var(--shadow-sm);}
        .card-header{background:var(--surface-2);border-bottom:1px solid var(--border);font-family:'Barlow',sans-serif;font-weight:700;color:var(--primary);padding:12px 18px;font-size:13px;border-radius:var(--radius) var(--radius) 0 0!important;letter-spacing:.1px;}

        /* ── Sidebar ──────────────────────────────────────────── */
        .site-wrapper{display:flex;min-height:100vh;transition:padding-left var(--tr);position:relative;}
        .cat-sidebar{width:220px;flex-shrink:0;background:#fff;border-right:1px solid var(--border);padding:0;position:fixed;top:60px;bottom:0;left:0;z-index:1020;overflow-y:auto;scrollbar-width:thin;transform:translateX(0);transition:transform var(--tr);box-shadow:2px 0 12px rgba(0,0,0,.03);}
        body.sidebar-closed .cat-sidebar{transform:translateX(-100%);}
        .site-main{flex:1;min-width:0;padding-left:220px;transition:padding-left var(--tr);}
        body.sidebar-closed .site-main{padding-left:0;}
        
        .cat-sidebar::-webkit-scrollbar{width:3px;}
        .cat-sidebar::-webkit-scrollbar-thumb{background:var(--border);border-radius:3px;}
        .cat-sidebar .sidebar-title{font-family:'Barlow',sans-serif;font-size:17px;font-weight:800;color:rgb(215,42,78);padding:14px 16px 10px;border-bottom:1px solid var(--border);letter-spacing:.2px;}
        .cat-sidebar .cat-item{display:flex;align-items:center;gap:10px;padding:9px 16px;font-size:13px;font-weight:500;color:var(--primary);text-decoration:none;border-bottom:1px solid rgba(0,0,0,.04);transition:background var(--tr);}
        .cat-sidebar .cat-item:hover{background:var(--surface-2);color:rgb(215,42,78);}
        .cat-sidebar .cat-item img{width:22px;height:22px;object-fit:cover;border-radius:3px;flex-shrink:0;}
        .cat-sidebar .cat-item i{width:22px;text-align:center;font-size:1rem;flex-shrink:0;opacity:.7;}

        /* ── Mobile sticky bottom nav ─────────────────────────── */
        .mobile-bottom-nav{display:none;position:fixed;bottom:0;left:0;right:0;height:56px;background:#fff;border-top:1px solid var(--border);z-index:1050;padding-bottom:env(safe-area-inset-bottom,0);}
        .mobile-bottom-nav .nav-tabs-inner{display:flex;height:56px;align-items:stretch;}
        .mobile-bottom-nav .m-nav-item{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;font-size:10px;font-weight:600;color:var(--text-2);text-decoration:none;border:none;background:none;cursor:pointer;transition:color var(--tr);padding:0;}
        .mobile-bottom-nav .m-nav-item:hover,.mobile-bottom-nav .m-nav-item.active{color:var(--primary);}
        .mobile-bottom-nav .m-nav-item i{font-size:1.2rem;}
        .mobile-bottom-nav .m-nav-home{width:48px;height:48px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;margin-top:-8px;box-shadow:0 4px 12px rgba(0,44,54,.35);}
        body.has-mobile-nav{padding-bottom:56px;}
        @media(max-width:991px){
            .site-main{padding-left:0;}
            body.sidebar-closed .site-main{padding-left:0;}
            .mobile-bottom-nav{display:block;}
        }

        /* Responsive */
        @media(max-width:991px){
            .navbar-main .container{height:auto;flex-wrap:wrap;padding:10px 14px;gap:10px;}
            .search-form{max-width:100%;order:3;width:100%;margin:8px 0 0;}
            .nav-links{gap:0;}
            .chaldal-cart-action{opacity: 1; bottom: 4px; width: 100px;}
            .chaldal-cart-action .btn-chaldal-add{font-size: 11px; padding: 4px 0;}
            .chaldal-qty-controls button{padding: 4px 8px; font-size: 13px;}
        }
        @media(max-width:768px){
            .hero-swiper{height:320px;}.hero-content h1{font-size:2rem;}
            .swiper-button-next, .swiper-button-prev { display: none !important; }
            .section-title{font-size:1.35rem;}.section-pad{padding:48px 0;}
            .promo-banner{padding:28px 20px;}
            .trust-item{padding:14px;}
            .product-card-img img{height:180px;}
        }
        @media(max-width:576px){
            .hero-swiper{height:240px;}.hero-content h1{font-size:1.6rem;}
            body{font-size:13px;}
        }
    </style>


    @stack('styles')
</head>
<body>

<div id="page-progress"></div>

{{-- ── HEADER ──────────────────────────────────────────────────── --}}
<nav class="navbar-main" id="mainNav">
    <div class="container" style="max-width:1280px">

        {{-- Desktop Sidebar Toggle --}}
        <button id="desktopSidebarToggle" class="btn d-none d-lg-flex" type="button" style="background:transparent;border:1px solid var(--border);color:var(--primary);padding:0;width:40px;height:40px;align-items:center;justify-content:center;border-radius:6px;transition:all var(--tr)">
            <i class="bi bi-list" style="font-size:1.4rem"></i>
        </button>

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            @if(\App\Models\Setting::getValue('logo'))
                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('logo')) }}" alt="{{ \App\Models\Setting::getValue('app_name', 'Modern Touch') }}" height="40" style="object-fit:contain">
            @else
                Modern <span class="brand-accent">Touch</span>
            @endif
        </a>

        {{-- Centre Search --}}
        <form class="search-form flex-grow-1 d-none d-md-flex ms-3" action="{{ route('search') }}" method="GET" style="max-width:540px;margin:0 auto">
            <input class="form-control" name="q" placeholder="Search products..." value="{{ request('q') }}" autocomplete="off">
            <span style="display:flex;align-items:center;padding:0 12px;cursor:pointer;color:var(--text-3)" title="Image search">
                <i class="bi bi-camera" style="font-size:1.1rem"></i>
            </span>
            <button class="btn" type="submit" style="background:var(--primary);color:#fff;border-radius:0 6px 6px 0;padding:0 16px;height:38px;display:flex;align-items:center">
                <i class="bi bi-search"></i>
            </button>
        </form>

        {{-- Right icons --}}
        <div class="d-flex align-items-center gap-2 ms-auto">
            <a class="nav-icon-btn" href="{{ route('cart.index') }}" title="Cart">
                <i class="bi bi-cart3" style="font-size:1.3rem"></i>
                <span class="cart-badge cart-count">0</span>
            </a>
            @auth
            <a class="nav-icon-btn" href="{{ route('account.wishlist') }}" title="Wishlist">
                <i class="bi bi-heart" style="font-size:1.3rem"></i>
            </a>
            <a class="nav-icon-btn" href="{{ route('account.profile') }}" title="My Account">
                <i class="bi bi-person" style="font-size:1.3rem"></i>
            </a>
            @else
            <a class="nav-icon-btn" href="{{ route('login') }}" title="Login">
                <i class="bi bi-person" style="font-size:1.3rem"></i>
            </a>
            @endauth
            @auth
                @if(auth()->user()->isManager())
                    <a class="btn-admin ms-1" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-1x2 me-1"></i>Admin</a>
                @endif
            @endauth

            {{-- Mobile hamburger (search toggle) --}}
            <button class="navbar-toggler d-md-none" type="button" id="mobileSearchToggle">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Search Bar --}}
    <div id="mobileSearchBar" style="display:none;padding:8px 14px;border-top:1px solid var(--border)">
        <form class="search-form w-100" action="{{ route('search') }}" method="GET">
            <input class="form-control" name="q" placeholder="Search products..." value="{{ request('q') }}">
            <button class="btn" type="submit" style="background:var(--primary);color:#fff;border-radius:0 6px 6px 0;padding:0 16px;height:38px;display:flex;align-items:center">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</nav>



{{-- Flash Messages --}}
<div class="flash-messages">
    @foreach(['success', 'error', 'info', 'warning'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show shadow" role="alert">
                <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : 'info-circle') }}-fill me-2"></i>
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach
</div>

{{-- ── SITE WRAPPER (sidebar + main) ──────────────────────────── --}}
<div class="site-wrapper">

{{-- LEFT CATEGORY SIDEBAR (desktop only) --}}
@php
    $sidebarCategories = \App\Models\Category::where('is_active', true)
        ->whereNull('parent_id')
        ->with(['children' => function($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])
        ->orderBy('sort_order')
        ->get();
@endphp
<aside class="cat-sidebar d-none d-lg-block">
    <div class="sidebar-title" style="display:flex; align-items:center; justify-content:space-between;">
        <span>&#128721; Category</span>
    </div>
    @foreach($sidebarCategories as $sc)
        @if($sc->children->isNotEmpty())
            <div>
                <a class="cat-item" data-bs-toggle="collapse" href="#cat-{{ $sc->id }}" role="button" aria-expanded="false" style="justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:10px">
                        @if($sc->image)
                            <img src="{{ asset('storage/'.$sc->image) }}" alt="">
                        @else
                            <i class="bi bi-grid-3x3-gap"></i>
                        @endif
                        <span>{{ $sc->name }}</span>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size:10px;width:auto;opacity:0.5;"></i>
                </a>
                <div class="collapse" id="cat-{{ $sc->id }}">
                    <div style="background:#f8fafc;border-bottom:1px solid rgba(0,0,0,.04);">
                        @foreach($sc->children as $child)
                        <a href="{{ route('shop.category', $child->slug) }}" class="cat-item" style="padding-left:48px;border-bottom:none;background:transparent;">
                            <span>{{ $child->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('shop.category', $sc->slug) }}" class="cat-item" style="justify-content:space-between">
                <div style="display:flex;align-items:center;gap:10px">
                    @if($sc->image)
                        <img src="{{ asset('storage/'.$sc->image) }}" alt="">
                    @else
                        <i class="bi bi-grid-3x3-gap"></i>
                    @endif
                    <span>{{ $sc->name }}</span>
                </div>
                <i class="bi bi-chevron-right" style="font-size:10px;width:auto;opacity:0.3;"></i>
            </a>
        @endif
    @endforeach
    <a href="{{ route('shop') }}" class="cat-item" style="color:var(--primary);font-weight:600">
        <i class="bi bi-arrow-right-circle"></i><span>View All Products</span>
    </a>
</aside>

{{-- MAIN CONTENT --}}
<div class="site-main">
@yield('content')
</div>{{-- .site-main --}}
</div>{{-- .site-wrapper --}}

{{-- ═══ TRUST BADGES (PRE-FOOTER) ════════════════════════════════════ --}}
<div class="container mb-4 mt-5" style="max-width:1280px">
    <div class="trust-bar" style="border:1px solid #e5e7eb; border-radius:6px; background:#fff">
        <div class="row g-0 divide-x position-relative">
            <div class="col-6 col-md-3"><div class="trust-item py-4 px-4"><div class="trust-icon" style="background:#f1f5f9; color:#0f4a6e; width:40px; height:40px; border-radius:8px"><i class="bi bi-truck"></i></div><div><h6 style="color:#0f4a6e; font-size:13px; font-weight:800; font-family:Inter">Fast Delivery</h6><small style="color:#64748b; font-size:12px">Nationwide shipping</small></div></div></div>
            <div class="col-6 col-md-3"><div class="trust-item py-4 px-4" style="border-left:1px solid #e5e7eb"><div class="trust-icon" style="background:#f1f5f9; color:#0f4a6e; width:40px; height:40px; border-radius:8px"><i class="bi bi-shield-check"></i></div><div><h6 style="color:#0f4a6e; font-size:13px; font-weight:800; font-family:Inter">Quality Guaranteed</h6><small style="color:#64748b; font-size:12px">ISO-grade steel</small></div></div></div>
            <div class="col-6 col-md-3"><div class="trust-item py-4 px-4" style="border-left:1px solid #e5e7eb"><div class="trust-icon" style="background:#f1f5f9; color:#0f4a6e; width:40px; height:40px; border-radius:8px"><i class="bi bi-headset"></i></div><div><h6 style="color:#0f4a6e; font-size:13px; font-weight:800; font-family:Inter">Expert Support</h6><small style="color:#64748b; font-size:12px">7 days a week</small></div></div></div>
            <div class="col-6 col-md-3"><div class="trust-item py-4 px-4" style="border-left:1px solid #e5e7eb"><div class="trust-icon" style="background:#f1f5f9; color:#0f4a6e; width:40px; height:40px; border-radius:8px"><i class="bi bi-award"></i></div><div><h6 style="color:#0f4a6e; font-size:13px; font-weight:800; font-family:Inter">Custom Orders</h6><small style="color:#64748b; font-size:12px">Bulk & enterprise</small></div></div></div>
        </div>
    </div>
</div>

{{-- ── FOOTER ──────────────────────────────────────────────────── --}}
<footer style="margin-top:0">
    <div class="container" style="max-width:1280px">
        <div class="row g-5">
            {{-- Brand Col --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">Modern <span>Touch</span></div>
                <p class="footer-desc">{{ \App\Models\Setting::getValue('tagline', 'Your trusted source for premium Industrial Furniture, Steel Racking, and Shelving Solutions in Bangladesh.') }}</p>
                <div class="footer-social">
                    @if($fb = \App\Models\Setting::getValue('facebook'))<a href="{{ $fb }}" title="Facebook"><i class="bi bi-facebook"></i></a>@endif
                    @if($yt = \App\Models\Setting::getValue('youtube'))<a href="{{ $yt }}" title="YouTube"><i class="bi bi-youtube"></i></a>@endif
                    @if($wa = \App\Models\Setting::getValue('whatsapp'))<a href="https://wa.me/{{ $wa }}" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>@endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6 col-6">
                <h6>Quick Links</h6>
                <div class="d-flex flex-column">
                    <a href="{{ route('home') }}" style="line-height:2.4;font-size:.875rem">Home</a>
                    <a href="{{ route('shop') }}" style="line-height:2.4;font-size:.875rem">All Products</a>
                    <a href="{{ route('contact') }}" style="line-height:2.4;font-size:.875rem">Contact Us</a>
                    @auth
                    <a href="{{ route('account.orders') }}" style="line-height:2.4;font-size:.875rem">My Orders</a>
                    @endauth
                </div>
            </div>

            {{-- Contact --}}
            <div class="col-lg-3 col-md-6 col-6">
                <h6>Contact</h6>
                <div style="font-size:.875rem;line-height:2;color:rgba(255,255,255,.5)">
                    <div class="mb-2"><i class="bi bi-geo-alt-fill me-2" style="color:var(--teal)"></i>{{ \App\Models\Setting::getValue('address', 'Tetuljhora College Road, Jorpool, Hemayetpur, Savar, Dhaka, Bangladesh') }}</div>
                    <div class="mb-2"><i class="bi bi-telephone-fill me-2" style="color:var(--teal)"></i>{{ \App\Models\Setting::getValue('phone', '+8801844696200') }}</div>
                    <div><i class="bi bi-envelope-fill me-2" style="color:var(--teal)"></i>{{ \App\Models\Setting::getValue('email', 'info@moderntouchbd.com') }}</div>
                </div>
            </div>

            {{-- Newsletter --}}
            <div class="col-lg-3 col-md-6">
                <h6>Newsletter</h6>
                <p style="font-size:.875rem;color:rgba(255,255,255,.45);margin-bottom:16px">Get the latest products and exclusive deals directly in your inbox.</p>
                <div class="footer-newsletter">
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="Your email address" required>
                            <button class="btn" type="submit"><i class="bi bi-send-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p>&copy; {{ date('Y') }} Modern Touch. All rights reserved.</p>
                <div>
                    <a href="{{ route('home') }}" style="color:rgba(255,255,255,.3);font-size:.8rem;margin-left:20px;transition:color .2s">Privacy Policy</a>
                    <a href="{{ route('home') }}" style="color:rgba(255,255,255,.3);font-size:.8rem;margin-left:20px;transition:color .2s">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- ── MOBILE STICKY BOTTOM NAV ──────────────────── --}}
<nav class="mobile-bottom-nav d-lg-none">
    <div class="nav-tabs-inner">
        <button class="m-nav-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileCategoryOffcanvas" aria-controls="mobileCategoryOffcanvas">
            <i class="bi bi-grid"></i><span>Category</span>
        </button>
        @auth
        <a href="{{ route('account.profile') }}" class="m-nav-item">
            <i class="bi bi-person"></i><span>Account</span>
        </a>
        @else
        <a href="{{ route('login') }}" class="m-nav-item">
            <i class="bi bi-person"></i><span>Login</span>
        </a>
        @endauth
        <a href="{{ route('home') }}" class="m-nav-item">
            <div class="m-nav-home"><i class="bi bi-house-fill"></i></div>
        </a>
        <a href="tel:{{ \App\Models\Setting::getValue('phone', '+8801844696200') }}" class="m-nav-item">
            <i class="bi bi-telephone"></i><span>Call</span>
        </a>
        <a href="{{ route('cart.index') }}" class="m-nav-item" style="position:relative">
            <i class="bi bi-cart3"></i>
            <span class="cart-count">0</span>
        </a>
    </div>
</nav>

{{-- Mobile Category Offcanvas --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileCategoryOffcanvas" aria-labelledby="mobileCategoryOffcanvasLabel" style="width:280px">
    <div class="offcanvas-header" style="border-bottom:1px solid var(--border)">
        <h6 class="offcanvas-title fw-bold" id="mobileCategoryOffcanvasLabel" style="color:rgb(215,42,78); font-family:'Barlow', sans-serif;">&#128721; Category</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="accordion accordion-flush" id="mobileSidebarAccordion">
            @if(isset($sidebarCategories))
                @foreach($sidebarCategories as $index => $sc)
                    @if($sc->children->isNotEmpty())
                        <div class="accordion-item" style="border:none;border-bottom:1px solid #f1f5f9;">
                            <h2 class="accordion-header" style="margin:0">
                                <button class="accordion-button collapsed py-3 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#m-cat-collapse-{{ $sc->id }}" style="box-shadow:none;font-size:14px;color:#002c36;display:flex;align-items:center;justify-content:space-between;width:100%;background:transparent;border:none">
                                    <div style="display:flex;align-items:center;gap:10px">
                                    @if($sc->image)
                                        <img src="{{ asset('storage/'.$sc->image) }}" alt="" style="width:22px;height:22px;object-fit:cover;border-radius:3px">
                                    @else
                                        <i class="bi bi-grid-3x3-gap" style="opacity:0.7;"></i>
                                    @endif
                                    <span style="font-weight:500">{{ $sc->name }}</span>
                                    </div>
                                    <i class="bi bi-chevron-down" style="font-size:10px;opacity:0.5;"></i>
                                </button>
                            </h2>
                            <div id="m-cat-collapse-{{ $sc->id }}" class="accordion-collapse collapse" data-bs-parent="#mobileSidebarAccordion">
                                <div class="accordion-body" style="padding:0;background:#f8fafc">
                                    @foreach($sc->children as $child)
                                    <a href="{{ route('shop.category', $child->slug) }}" style="padding:10px 20px 10px 45px;font-size:13px;color:#475569;display:block;text-decoration:none;border-bottom:1px solid rgba(0,0,0,.02)">
                                        {{ $child->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('shop.category', $sc->slug) }}" class="d-flex align-items-center justify-content-between py-3 px-3" style="font-size:14px;color:#002c36;border-bottom:1px solid #f1f5f9;text-decoration:none;">
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($sc->image)
                                    <img src="{{ asset('storage/'.$sc->image) }}" alt="" style="width:22px;height:22px;object-fit:cover;border-radius:3px">
                                @else
                                    <i class="bi bi-grid-3x3-gap" style="opacity:0.7;"></i>
                                @endif
                                <span style="font-weight:500">{{ $sc->name }}</span>
                            </div>
                            <i class="bi bi-chevron-right" style="font-size:10px;color:#94a3b8"></i>
                        </a>
                    @endif
                @endforeach
            @endif
            <a href="{{ route('shop') }}" class="d-flex align-items-center gap-2 py-3 px-3" style="color:var(--primary);font-weight:600;text-decoration:none">
                <i class="bi bi-arrow-right-circle"></i><span>View All Products</span>
            </a>
        </div>
    </div>
</div>

{{-- Scroll to top button --}}
<button id="scroll-top" title="Back to top"><i class="bi bi-arrow-up"></i></button>

{{-- Bootstrap 5 JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Swiper JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//  Page Progress Bar ──────────────────────────────────
const progress = document.getElementById('page-progress');
if (progress) {
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                const pct = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
                progress.style.width = pct + '%';
                ticking = false;
            });
            ticking = true;
        }
    });
}

//  Sidebar Toggle
document.getElementById('desktopSidebarToggle')?.addEventListener('click', () => {
    document.body.classList.toggle('sidebar-closed');
});

//  Sticky Navbar Scroll Effect 
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => {
    nav?.classList.toggle('scrolled', window.scrollY > 80);
    const btn = document.getElementById('scroll-top');
    btn?.classList.toggle('visible', window.scrollY > 400);
}, { passive: true });

//  Scroll to Top 
document.getElementById('scroll-top')?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

//  Scroll Reveal Observer 
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); revealObserver.unobserve(e.target); } });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => revealObserver.observe(el));

//  Auto-dismiss Flash Messages 
document.querySelectorAll('.flash-messages .alert').forEach(el => {
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 300); }, 4500);
});

//  Cart Count & Items Map
window.cartItems = {};
function fetchCartData() {
    fetch('{{ route("cart.count") }}', { headers: { 'Accept': 'application/json' } })
        .then(r => r.ok ? r.json() : null)
        .then(d => { 
            if (d) {
                document.querySelectorAll('.cart-count').forEach(el => el.textContent = d.count); 
                window.cartItems = d.items || {};
                updateProductCartUI();
            }
        })
        .catch(() => {});
}
fetchCartData();

// Render inline UI
function updateProductCartUI() {
    document.querySelectorAll('.chaldal-cart-action').forEach(container => {
        const pId = container.dataset.productId;
        const qty = window.cartItems[pId] || 0;
        const addBtn = container.querySelector('.btn-chaldal-add');
        const qtyCtrl = container.querySelector('.chaldal-qty-controls');
        const qtyVal = container.querySelector('.chaldal-qty-val');
        
        if (qty > 0) {
            container.classList.add('has-items');
            addBtn.style.display = 'none';
            qtyCtrl.style.display = 'flex';
            qtyVal.textContent = qty;
        } else {
            container.classList.remove('has-items');
            addBtn.style.display = 'flex';
            qtyCtrl.style.display = 'none';
            qtyVal.textContent = 0;
        }
    });
}

// Global updateCartQty for Chaldal-style
function updateCartQty(productId, actionOrQty) {
    let currentQty = window.cartItems[productId] || 0;
    let newQty = currentQty;
    
    if (actionOrQty === 'inc') {
        newQty = currentQty + 1;
    } else if (actionOrQty === 'dec') {
        newQty = currentQty - 1;
    } else {
        newQty = typeof actionOrQty === 'number' ? actionOrQty : parseInt(actionOrQty, 10);
    }
    
    if (newQty < 0) newQty = 0;
    
    // Optimistic UI update
    window.cartItems[productId] = newQty;
    updateProductCartUI();
    
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch('{{ route("cart.update-item-qty") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: newQty })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.querySelectorAll('.cart-count').forEach(el => el.textContent = d.count);
            window.cartItems = d.items || {};
            updateProductCartUI();
            
            // Show toast only on initial add
            if (currentQty === 0 && newQty > 0) {
                Swal.fire({
                    icon: 'success', title: 'Added to Cart!',
                    toast: true, position: 'top-end',
                    showConfirmButton: false, timer: 1500,
                    timerProgressBar: true,
                    background: '#0D7377', color: '#fff',
                    iconColor: '#F0A500'
                });
            }
        }
    }).catch(() => {
        // revert on failure
        fetchCartData();
    });
}

//  AJAX Add to Cart (legacy)
function addToCart(productId, variantId = null, qty = 1) {
    return updateCartQty(productId, qty);
}
</script>
@stack('scripts')
</body>
</html>
