@extends('layouts.app')
@section('title', 'My Wallet')

@section('content')
<div class="breadcrumb-section">
    <div class="container" style="max-width:1280px">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">My Account</a></li>
            <li class="breadcrumb-item active">My Wallet</li>
        </ol>
    </div>
</div>

<section style="padding:32px 0 64px">
    <div class="container" style="max-width:1100px">
        <div class="row g-4">
            {{-- Sidebar --}}
            <div class="col-lg-3">
                @include('account._sidebar', ['active' => 'wallet'])
            </div>

            {{-- Main Content --}}
            <div class="col-lg-9">
                {{-- Balance Card --}}
                <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 12px;">
                    <div class="card-body p-0">
                        <div class="p-4 d-flex align-items-center justify-content-between" 
                             style="background: linear-gradient(135deg, var(--teal), var(--primary)); color: #fff;">
                            <div>
                                <div style="font-size: .85rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; font-weight: 500;">Wallet Balance</div>
                                <div style="font-size: 2.2rem; font-weight: 800; font-family: 'Inter', sans-serif; line-height: 1.1;">৳ {{ number_format($wallet->balance, 2) }}</div>
                            </div>
                            <div style="font-size: 3.5rem; opacity: 0.2;">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-light d-flex align-items-center gap-2" style="font-size: .85rem; color: var(--text-2);">
                            <i class="bi bi-info-circle text-primary"></i>
                            Use your wallet balance to pay for orders during checkout!
                        </div>
                    </div>
                </div>

                {{-- Transaction History --}}
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-700"><i class="bi bi-clock-history me-2 text-primary"></i>Transaction History</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: .9rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Amount</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $tx)
                                    <tr>
                                        <td class="text-muted" style="white-space: nowrap;">
                                            {{ $tx->created_at->format('M d, Y') }} <br>
                                            <span style="font-size: .75rem;">{{ $tx->created_at->format('H:i A') }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-700" style="color: {{ $tx->type === 'credit' ? '#059669' : '#DC2626' }}">
                                                {{ $tx->type === 'credit' ? '+' : '-' }} ৳ {{ number_format($tx->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $tx->type === 'credit' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" 
                                                  style="font-size: .65rem; border: none; font-weight: 600; text-transform: uppercase;">
                                                {{ $tx->type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-600 text-dark">{{ $tx->description }}</div>
                                            @if($tx->reference_type && $tx->reference_id)
                                                <div style="font-size: .75rem; color: var(--text-3);">Ref: {{ str_replace('_', ' ', $tx->reference_type) }} #{{ $tx->reference_id }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <img src="{{ asset('assets/img/empty-wallet.svg') }}" alt="" style="width: 120px; opacity: 0.1; display: block; margin: 0 auto 15px;">
                                            No transactions found yet.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($transactions->hasPages())
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
