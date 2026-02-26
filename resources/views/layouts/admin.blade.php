<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Modern Touch BD Admin</title>
    <link rel="icon" href="{{ asset('storage/' . (\App\Models\Setting::getValue('favicon') ?? 'images/favicon.ico')) }}">

    {{-- Bootstrap 5 + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts: Inter + DM Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <style>
        /* ════════════════════════════════════════
           ADMIN DESIGN SYSTEM — Modern Touch BD
        ════════════════════════════════════════ */
        :root {
            --sidebar-w:   260px;
            --primary:     #1A3A5C;
            --teal:        #0D7377;
            --teal-light:  #14919B;
            --gold:        #F0A500;
            --danger:      #EF4444;
            --success:     #10B981;
            --dark:        #0A1628;
            --sidebar-bg:  #0D1F35;
            --header-bg:   #FFFFFF;
            --body-bg:     #F1F4F8;
            --surface:     #FFFFFF;
            --surface-2:   #F1F3F7;
            --border:      #E2E6EE;
            --text:        #111827;
            --text-2:      #4B5563;
            --text-3:      #9CA3AF;
            --radius:      10px;
            --radius-lg:   14px;
            --shadow-sm:   0 1px 3px rgba(0,0,0,.07);
            --shadow:      0 4px 16px rgba(0,0,0,.08);
            --tr:          .2s cubic-bezier(.4,0,.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            background: var(--body-bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        h1,h2,h3,h4,h5,h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }
        a { text-decoration: none; color: var(--teal); }

        /* ── SIDEBAR ─────────────────────────────── */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            position: fixed; top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 200;
            display: flex; flex-direction: column;
            transition: transform var(--tr);
            box-shadow: 2px 0 20px rgba(0,0,0,.25);
        }
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 2px; }

        /* Brand */
        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--teal), var(--primary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Inter', sans-serif; font-weight: 900;
            color: #fff; font-size: 1rem; flex-shrink: 0;
        }
        .sidebar-brand-text h5 {
            color: #fff; font-weight: 800; margin: 0;
            font-size: .95rem; font-family: 'Inter', sans-serif;
            letter-spacing: -.2px; line-height: 1.1;
        }
        .sidebar-brand-text small {
            color: var(--teal); font-size: .65rem;
            letter-spacing: 1.5px; text-transform: uppercase;
        }

        /* User */
        .sidebar-user {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--primary));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .85rem;
            flex-shrink: 0; font-family: 'Inter', sans-serif;
        }
        .sidebar-user-name { color: rgba(255,255,255,.9); font-weight: 600; font-size: .82rem; }
        .sidebar-user-role { color: var(--teal); font-size: .7rem; text-transform: uppercase; letter-spacing: .8px; }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 12px 0; }
        .nav-section-title {
            padding: 10px 20px 4px;
            font-size: .62rem; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: rgba(255,255,255,.22);
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px;
            color: rgba(255,255,255,.5);
            font-size: .84rem; font-weight: 500;
            transition: all var(--tr);
            border-left: 2px solid transparent;
            cursor: pointer;
        }
        .sidebar-link:hover {
            color: rgba(255,255,255,.9);
            background: rgba(255,255,255,.05);
            border-left-color: var(--teal);
            color: rgba(255,255,255,.9);
        }
        .sidebar-link.active {
            color: #fff;
            background: rgba(13,115,119,.18);
            border-left-color: var(--teal);
        }
        .sidebar-link i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }
        .badge-count {
            background: var(--teal); color: #fff;
            font-size: .62rem; padding: 2px 7px;
            border-radius: 10px; margin-left: auto; font-weight: 700;
        }

        /* ── MAIN CONTENT ────────────────────────── */
        .admin-main { margin-left: var(--sidebar-w); min-height: 100vh; }

        /* Topbar */
        .admin-topbar {
            background: var(--header-bg);
            padding: 0 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 8px rgba(0,0,0,.05);
            height: 58px;
        }
        .admin-topbar .breadcrumb { margin: 0; font-size: .84rem; }
        .breadcrumb-item a { color: var(--teal); }
        .breadcrumb-item.active { color: var(--text-2); }
        .breadcrumb-item + .breadcrumb-item::before { color: var(--text-3); }

        /* Content area */
        .admin-content { padding: 28px; }
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .page-header h4 {
            font-weight: 800; color: var(--primary); margin: 0;
            font-size: 1.4rem; letter-spacing: -.3px;
        }
        .page-header-sub { color: var(--text-3); font-size: .82rem; margin-top: 2px; }

        /* ── STAT / KPI CARDS ────────────────────── */
        .stat-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 22px;
            transition: transform var(--tr), box-shadow var(--tr);
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow); }
        .stat-card-revenue::before  { background: linear-gradient(90deg, var(--teal), var(--teal-light)); }
        .stat-card-orders::before   { background: linear-gradient(90deg, var(--gold), #F59E0B); }
        .stat-card-products::before { background: linear-gradient(90deg, #10B981, #34D399); }
        .stat-card-customers::before{ background: linear-gradient(90deg, #8B5CF6, #A78BFA); }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .stat-label {
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .8px; color: var(--text-3); margin-bottom: 4px;
        }
        .stat-value {
            font-family: 'Inter', sans-serif; font-size: 1.55rem;
            font-weight: 800; color: var(--text); letter-spacing: -.5px;
        }
        .stat-badge { margin-top: 6px; font-size: .75rem; font-weight: 600; }

        /* ── ADMIN CARDS ─────────────────────────── */
        .admin-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .admin-card .card-header {
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
            font-weight: 700; color: var(--primary);
            font-family: 'Inter', sans-serif;
            font-size: .88rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .admin-card .card-body { padding: 20px; }

        /* ── TABLES ──────────────────────────────── */
        .table thead th {
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
            font-size: .72rem; text-transform: uppercase;
            letter-spacing: .8px; color: var(--text-2);
            font-weight: 700; padding: 12px 16px;
            white-space: nowrap;
        }
        .table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle; font-size: .875rem;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table-hover tbody tr:hover { background: rgba(13,115,119,.04); }
        .table-row-actions .btn { padding: 4px 10px; font-size: .78rem; }

        /* ── STATUS BADGES ───────────────────────── */
        .badge-status-pending    { background: rgba(245,158,11,.12); color: #D97706; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
        .badge-status-confirmed  { background: rgba(6,182,212,.12); color: #0891B2; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
        .badge-status-processing { background: rgba(59,130,246,.12); color: #2563EB; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
        .badge-status-shipped    { background: rgba(139,92,246,.12); color: #7C3AED; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
        .badge-status-delivered  { background: rgba(16,185,129,.12); color: #059669; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }
        .badge-status-cancelled  { background: rgba(239,68,68,.12);  color: #DC2626; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap; }

        /* ── QUICK ACTION ITEMS ──────────────────── */
        .quick-action-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px;
            border-radius: var(--radius);
            border: 1.5px solid var(--border);
            transition: all var(--tr);
            cursor: pointer; color: var(--text);
        }
        .quick-action-item:hover {
            border-color: var(--teal);
            background: rgba(13,115,119,.06);
            color: var(--teal);
            transform: translateX(4px);
        }
        .quick-action-item i { font-size: 1.05rem; color: var(--teal); flex-shrink: 0; }

        /* ── FORMS ───────────────────────────────── */
        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            color: var(--text); font-size: .875rem;
            padding: 9px 13px;
            transition: border-color var(--tr), box-shadow var(--tr);
            background: #fff;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(13,115,119,.12);
            outline: none;
        }
        .form-label {
            font-weight: 600; font-size: .8rem; color: var(--text);
            margin-bottom: 5px; text-transform: uppercase; letter-spacing: .4px;
        }
        .input-group-text { background: var(--surface-2); border-color: var(--border); color: var(--text-2); }
        .form-check-input:checked { background-color: var(--teal); border-color: var(--teal); }

        /* ── BUTTONS ─────────────────────────────── */
        .btn { font-weight: 600; border-radius: var(--radius); transition: all var(--tr); }
        .btn:active { transform: scale(.97); }
        .btn-primary, .btn-primary:focus {
            background: var(--teal) !important;
            border-color: var(--teal) !important;
            color: #fff !important;
        }
        .btn-primary:hover { background: var(--teal-light) !important; transform: translateY(-1px); }
        .btn-outline-primary {
            border-color: var(--teal) !important;
            color: var(--teal) !important;
            background: transparent !important;
        }
        .btn-outline-primary:hover { background: var(--teal) !important; color: #fff !important; }
        .btn-warning  { background: var(--gold) !important; border-color: var(--gold) !important; color: #fff !important; }
        .btn-dark     { background: var(--dark) !important; border-color: var(--dark) !important; color: #fff !important; }
        .btn-danger   { transition: all var(--tr) !important; }
        .btn-sm       { padding: 5px 12px; font-size: .8rem; }

        /* ── ALERTS ──────────────────────────────── */
        .alert { border-radius: var(--radius); border: none; }
        .alert-success  { background: rgba(16,185,129,.1); color: #065F46; }
        .alert-danger   { background: rgba(239,68,68,.1); color: #991B1B; }
        .alert-info     { background: rgba(59,130,246,.1); color: #1E40AF; }
        .alert-warning  { background: rgba(245,158,11,.1); color: #92400E; }

        /* ── MISC ────────────────────────────────── */
        .text-primary { color: var(--teal) !important; }
        .badge.bg-primary-subtle { background: rgba(13,115,119,.12) !important; color: var(--teal) !important; }
        .fw-500 { font-weight: 500 !important; }
        .fw-600 { font-weight: 600 !important; }
        .fw-700 { font-weight: 700 !important; }

        /* ── RESPONSIVE ──────────────────────────── */
        @media (max-width: 991px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-main { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ──────────────────────────────────────────── --}}
<div class="admin-sidebar" id="adminSidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-logo">MT</div>
        <div class="sidebar-brand-text">
            <h5>Modern Touch BD</h5>
            <small>Admin Panel</small>
        </div>
    </div>

    {{-- User --}}
    @auth
    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
            <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'Admin')) }}</div>
        </div>
    </div>
    @endauth

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-section-title">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="nav-section-title">Catalog</div>
        @can('manage_categories')
        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-folder2-open"></i> Categories
        </a>
        @endcan
        @can('manage_products')
        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Products
        </a>
        @endcan
        @can('manage_tags')
        <a href="{{ route('admin.tags.index') }}" class="sidebar-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Tags
        </a>
        @endcan

        <div class="nav-section-title">Sales</div>
        @can('manage_orders')
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Orders
            @php $pendingOrders = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pendingOrders > 0)<span class="badge-count">{{ $pendingOrders }}</span>@endif
        </a>
        @endcan
        @can('manage_customers')
        <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customers
        </a>
        @endcan

        <div class="nav-section-title">Marketing</div>
        @can('manage_coupons')
        <a href="{{ route('admin.coupons.index') }}" class="sidebar-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> Coupons
        </a>
        @endcan
        @can('manage_sliders')
        <a href="{{ route('admin.sliders.index') }}" class="sidebar-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
            <i class="bi bi-image"></i> Sliders / Banners
        </a>
        @endcan
        @can('manage_newsletters')
        <a href="{{ route('admin.newsletters.index') }}" class="sidebar-link {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}">
            <i class="bi bi-envelope-at"></i> Newsletter
        </a>
        @endcan
        @can('manage_inquiries')
        <a href="{{ route('admin.inquiries.index') }}" class="sidebar-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i> Inquiries
            @php $unreadInquiries = \App\Models\Inquiry::unread()->count(); @endphp
            @if($unreadInquiries > 0)<span class="badge-count">{{ $unreadInquiries }}</span>@endif
        </a>
        @endcan
        @can('manage_reviews')
        <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Reviews
        </a>
        @endcan

        @can('manage_reports')
        <div class="nav-section-title">Reports</div>
        <a href="{{ route('admin.reports.sales') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Sales Reports
        </a>
        @endcan

        <div class="nav-section-title">Locations</div>
        @can('manage_shipping')
        <a href="{{ route('admin.cities.index') }}" class="sidebar-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Cities & Shipping
        </a>
        @endcan

        <div class="nav-section-title">System</div>
        @if(auth()->user() && auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Roles & Permissions
        </a>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Admin Users
        </a>
        @endif
        @can('manage_activity_log')
        <a href="{{ route('admin.activity.index') }}" class="sidebar-link {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Activity Log
        </a>
        @endcan
        @can('manage_settings')
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        <a href="{{ route('admin.payment-methods.index') }}" class="sidebar-link {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Payment Methods
        </a>
        @endcan

        <div style="margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,.07)">
            <a href="{{ route('home') }}" class="sidebar-link">
                <i class="bi bi-shop"></i> View Store
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start" style="color:rgba(255,100,100,.75)">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</div>

{{-- ── MAIN CONTENT ─────────────────────────────────────── --}}
<div class="admin-main">
    {{-- Topbar --}}
    <div class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-lg-none"
                    style="background:var(--surface-2);border:1px solid var(--border);border-radius:8px"
                    onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small d-none d-md-inline">{{ now()->format('D, d M Y') }}</span>
            @auth
            <span class="badge" style="background:rgba(13,115,119,.12);color:var(--teal);font-weight:600;font-size:.75rem">
                {{ ucfirst(auth()->user()->role ?? 'Admin') }}
            </span>
            @endauth
        </div>
    </div>

    {{-- Content --}}
    <div class="admin-content">
        {{-- Flash Messages --}}
        @foreach(['success', 'error', 'info', 'warning'] as $type)
            @if(session($type))
                <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'x-circle' : 'info-circle') }}-fill me-2"></i>
                    {{ session($type) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 300); }, 4000);
});

// Close sidebar on outside click (mobile)
document.addEventListener('click', e => {
    const sidebar = document.getElementById('adminSidebar');
    if (sidebar?.classList.contains('show') && !sidebar.contains(e.target)) {
        sidebar.classList.remove('show');
    }
});

// Confirm delete
function confirmDelete(formId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete it!',
        background: '#fff',
        borderRadius: '14px'
    }).then(result => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    return false;
}
</script>
@stack('scripts')
</body>
</html>
