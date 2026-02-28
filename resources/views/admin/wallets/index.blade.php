@extends('layouts.admin')
@section('title', 'Customer Wallets')
@section('breadcrumb')
<li class="breadcrumb-item active">Customer Wallets</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-wallet2 me-2" style="color:var(--teal)"></i>Customer Wallets</h4>
        <div class="page-header-sub">Manage and view digital wallet balances and transactions</div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-people me-2" style="color:var(--teal)"></i>Customer Wallets</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($wallets as $wallet)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="sidebar-avatar" style="width: 32px; height: 32px; font-size: .8rem;">{{ strtoupper(substr($wallet->user->name, 0, 1)) }}</div>
                            <span class="fw-600">{{ $wallet->user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $wallet->user->email }}</td>
                    <td><span class="fw-700 text-primary">৳ {{ number_format($wallet->balance, 2) }}</span></td>
                    <td>
                        <span class="badge {{ $wallet->status ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" style="font-size: .7rem; padding: 4px 8px;">
                            {{ $wallet->status ? 'Active' : 'Locked' }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $wallet->updated_at->format('d M, Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.wallets.show', $wallet) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-wallet2 fs-3 d-block mb-2"></i> No wallets found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($wallets->hasPages())
    <div class="card-footer py-3">
        {{ $wallets->links() }}
    </div>
    @endif
</div>
@endsection
