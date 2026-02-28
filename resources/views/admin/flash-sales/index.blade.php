@extends('layouts.admin')
@section('title', 'Flash Sales')
@section('breadcrumb')
<li class="breadcrumb-item active">Flash Sales</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-lightning-charge me-2" style="color:#ffc107"></i>Flash Sales</h4>
        <div class="page-header-sub">Manage time-bound promotional campaigns and special deals.</div>
    </div>
    <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> New Campaign
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-calendar-event me-2" style="color:var(--teal)"></i>All Campaigns</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Duration</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flashSales as $sale)
                <tr>
                    <td>
                        <div class="fw-600">{{ $sale->name }}</div>
                        <div class="text-muted small">URL: /flash-sale/{{ $sale->slug }}</div>
                    </td>
                    <td>
                        <div class="small">
                            <i class="bi bi-play-circle me-1 text-success"></i>{{ $sale->start_time->format('d M, Y H:i') }}<br>
                            <i class="bi bi-stop-circle me-1 text-danger"></i>{{ $sale->end_time->format('d M, Y H:i') }}
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $sale->products_count }} Products</span>
                    </td>
                    <td>
                        @php $isOpen = $sale->isOpen(); @endphp
                        <span class="badge {{ $isOpen ? 'bg-success' : 'bg-secondary' }}">
                            {{ $isOpen ? 'LIVE NOW' : ($sale->start_time > now() ? 'SCHEDULED' : 'ENDED') }}
                        </span>
                        @if(!$sale->is_active)
                            <span class="badge bg-danger ms-1">DISABLED</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group">
                            <a href="{{ route('admin.flash-sales.edit', $sale) }}" class="btn btn-sm btn-outline-primary" title="Edit/Manage Products">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.flash-sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Delete this campaign?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-lightning fs-1 d-block mb-2 opacity-25"></i>
                        No flash sales found. Create your first campaign to boost revenue!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
