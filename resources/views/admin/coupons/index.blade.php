@extends('layouts.admin')
@section('title', 'Coupons')
@section('breadcrumb')
<li class="breadcrumb-item active">Coupons</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-ticket-perforated me-2" style="color:var(--teal)"></i>Coupons</h4>
        <div class="page-header-sub">{{ $coupons->total() }} discount coupons</div>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Coupon
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Coupon List</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-weight:700;font-size:.9rem;color:var(--primary);background:var(--surface-2);padding:3px 10px;border-radius:6px;border:1px dashed var(--border)">
                            {{ $coupon->code }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size:.78rem;font-weight:600;color:var(--text-2)">
                            @if($coupon->type === 'percent')
                                <i class="bi bi-percent me-1" style="color:var(--teal)"></i>Percentage
                            @else
                                <i class="bi bi-currency-exchange me-1" style="color:var(--gold)"></i>Fixed
                            @endif
                        </span>
                    </td>
                    <td style="font-weight:700;font-family:'Inter',sans-serif;color:var(--primary)">
                        {{ $coupon->type === 'percent' ? $coupon->value.'%' : '৳'.number_format($coupon->value, 0) }}
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">
                        {{ $coupon->minimum_order ? '৳'.number_format($coupon->minimum_order, 0) : '—' }}
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">
                        {{ $coupon->used_count ?? 0 }}{{ $coupon->usage_limit ? '/'.$coupon->usage_limit : '' }}
                    </td>
                    <td style="font-size:.8rem;color:var(--text-2)">
                        @if($coupon->valid_until ?? $coupon->expires_at)
                            @php $exp = $coupon->valid_until ?? $coupon->expires_at; @endphp
                            @if($exp->isPast())
                                <span style="color:#EF4444;font-weight:600">Expired {{ $exp->format('d M Y') }}</span>
                            @else
                                {{ $exp->format('d M Y') }}
                            @endif
                        @else
                            <span style="color:var(--text-3)">Never</span>
                        @endif
                    </td>
                    <td>
                        @if($coupon->is_active)
                            <span style="background:rgba(13,115,119,.1);color:var(--teal);padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Active</span>
                        @else
                            <span style="background:rgba(107,114,128,.1);color:#6B7280;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 table-row-actions">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-coup-{{ $coupon->id }}" action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-coup-{{ $coupon->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-ticket-perforated fs-2 d-block mb-2" style="opacity:.4"></i>No coupons yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $coupons->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
