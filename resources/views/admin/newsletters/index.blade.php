@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')
@section('breadcrumb')
<li class="breadcrumb-item active">Newsletter</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-envelope-at me-2" style="color:var(--teal)"></i>Newsletter Subscribers</h4>
        <div class="page-header-sub">{{ $subscribers->total() }} subscribers</div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="admin-card">
            <div class="card-body d-flex align-items-center gap-3" style="padding:18px 20px">
                <div class="stat-icon" style="background:rgba(13,115,119,.1)"><i class="bi bi-people" style="color:var(--teal)"></i></div>
                <div>
                    <div class="stat-label">Total Subscribers</div>
                    <div class="stat-value" style="font-size:1.3rem">{{ $subscribers->total() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Subscriber List</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Email Address</th>
                    <th>Subscribed On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                <tr>
                    <td style="color:var(--text-3);font-size:.8rem">{{ $sub->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope" style="color:var(--teal)"></i>
                            <span style="font-size:.875rem">{{ $sub->email }}</span>
                        </div>
                    </td>
                    <td style="font-size:.8rem;color:var(--text-2)">
                        {{ $sub->created_at->format('d M Y, H:i') }}
                        <div style="font-size:.72rem;color:var(--text-3)">{{ $sub->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-envelope fs-2 d-block mb-2" style="opacity:.4"></i>No subscribers yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscribers->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $subscribers->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
