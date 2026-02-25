{{-- Shared account sidebar. Pass $active = 'profile'|'orders'|'addresses'|'wishlist' --}}
@auth
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3 text-center">
        @if(auth()->user()->avatar)
            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Avatar"
                 style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid var(--border);margin-bottom:10px">
        @else
            <div class="mx-auto mb-2" style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));display:flex;align-items:center;justify-content:center;font-size:1.7rem;font-weight:800;color:#fff;font-family:'Inter',sans-serif">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>
        @endif
        <div class="fw-700 text-dark" style="font-size:.9rem;line-height:1.2">{{ auth()->user()->name }}</div>
        <div class="text-muted" style="font-size:.73rem">{{ auth()->user()->email }}</div>
        <span class="badge mt-1" style="background:rgba(13,115,119,.12);color:var(--teal);font-size:.65rem">
            {{ ucfirst(str_replace('_',' ', auth()->user()->role ?? 'customer')) }}
        </span>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush" style="border-radius:var(--radius-lg)">
        @php
            $links = [
                'profile'   => ['route'=>'account.profile',   'icon'=>'bi-person-circle',  'label'=>'Profile'],
                'orders'    => ['route'=>'account.orders',    'icon'=>'bi-receipt',         'label'=>'My Orders'],
                'addresses' => ['route'=>'account.addresses', 'icon'=>'bi-geo-alt',         'label'=>'Addresses'],
                'wishlist'  => ['route'=>'account.wishlist',  'icon'=>'bi-heart',           'label'=>'Wishlist'],
            ];
        @endphp
        @foreach($links as $key => $link)
            @php $isActive = ($active ?? '') === $key; @endphp
            <a href="{{ route($link['route']) }}"
               class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-2"
               style="{{ $isActive ? 'background:var(--teal);color:#fff;font-weight:600;' : '' }}border:none;font-size:.875rem">
                <i class="bi {{ $link['icon'] }}" style="{{ $isActive ? 'color:#fff' : 'color:var(--teal)' }}"></i>
                {{ $link['label'] }}
            </a>
        @endforeach
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-2 border-0 text-danger w-100 text-start"
                    style="font-size:.875rem;background:transparent">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>
@endauth
