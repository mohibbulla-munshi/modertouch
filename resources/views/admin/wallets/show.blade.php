@extends('layouts.admin')
@section('title', 'Wallet Details - ' . $wallet->user->name)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.wallets.index') }}">Wallets</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.wallets.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; width: 34px; height: 34px; padding: 0; display: grid; place-items: center;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h4><i class="bi bi-wallet2 me-2" style="color:var(--teal)"></i>Wallet Details</h4>
                <div class="page-header-sub">Manage balance and view transaction history for <strong>{{ $wallet->user->name }}</strong></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Balance & Adjustment --}}
    <div class="col-lg-4">
        <div class="admin-card mb-4 text-center p-4" style="background: linear-gradient(135deg, var(--teal), var(--primary)); color: #fff;">
            <div class="mb-2" style="opacity: 0.8; font-size: .85rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Current Balance</div>
            <div style="font-size: 2.2rem; font-weight: 800; font-family: 'Inter', sans-serif;">৳ {{ number_format($wallet->balance, 2) }}</div>
            <div class="mt-2">
                <span class="badge {{ $wallet->status ? 'bg-white text-success' : 'bg-white text-danger' }}" style="font-weight: 700;">
                    {{ $wallet->status ? 'ACTIVE' : 'LOCKED' }}
                </span>
            </div>
        </div>

        <div class="admin-card">
            <div class="card-header"><i class="bi bi-plus-slash-minus me-2" style="color:var(--teal)"></i>Adjust Balance</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.wallets.adjust', $wallet) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="credit">Credit (Add Funds)</option>
                            <option value="debit">Debit (Deduct Funds)</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (৳)</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" name="amount" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00" required>
                        </div>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Reason / Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Explain why this adjustment is being made..." required></textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-check-circle me-1"></i>Apply Adjustment
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <span><i class="bi bi-clock-history me-2" style="color:var(--teal)"></i>Transaction History</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallet->transactions as $tx)
                        <tr>
                            <td class="text-muted small">#{{ $tx->id }}</td>
                            <td style="font-size: .8rem;">{{ $tx->created_at->format('d M, Y H:i') }}</td>
                            <td>
                                <span class="fw-700" style="color: {{ $tx->type === 'credit' ? '#059669' : '#DC2626' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }} ৳ {{ number_format($tx->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $tx->type === 'credit' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" style="font-size: .65rem; padding: 3px 7px;">
                                    {{ strtoupper($tx->type) }}
                                </span>
                            </td>
                            <td style="font-size: .75rem; color: var(--text-2);">
                                {{ str_replace('_', ' ', $tx->reference_type ?? 'Generic') }}
                            </td>
                            <td style="font-size: .8rem; font-weight: 500;">{{ $tx->description }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-text fs-3 d-block mb-2"></i> No transactions found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
