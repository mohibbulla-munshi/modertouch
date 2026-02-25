@extends('layouts.admin')
@section('title', 'Customers')
@section('breadcrumb')
<li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-people me-2" style="color:var(--teal)"></i>Customers</h4>
        <div class="page-header-sub">{{ $customers->total() }} registered customers</div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Customer List</span>
        <span style="font-size:.78rem;color:var(--text-3)">{{ $customers->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Orders</th>
                    <th>Joined</th>
                    <th style="width:80px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td style="color:var(--text-3);font-size:.8rem">{{ $customer->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600;font-size:.875rem">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">{{ $customer->email }}</td>
                    <td>
                        <span style="background:rgba(13,115,119,.1);color:var(--teal);padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">
                            {{ ucfirst($customer->role) }}
                        </span>
                    </td>
                    <td style="font-weight:700;color:var(--primary);font-family:'Inter',sans-serif">
                        {{ $customer->orders_count ?? $customer->orders()->count() }}
                    </td>
                    <td style="font-size:.8rem;color:var(--text-2)">{{ $customer->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-people fs-2 d-block mb-2" style="opacity:.4"></i>No customers yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
