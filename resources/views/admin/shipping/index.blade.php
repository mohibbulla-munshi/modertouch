@extends('layouts.admin')
@section('title', 'Shipping Zones')
@section('breadcrumb')
<li class="breadcrumb-item active">Shipping</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-truck me-2" style="color:var(--teal)"></i>Shipping Zones</h4>
        <div class="page-header-sub">{{ $zones->count() }} delivery zones configured</div>
    </div>
    <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Zone
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-geo-alt me-2" style="color:var(--teal)"></i>Shipping Zone List</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Zone Name</th>
                    <th>Regions / Areas</th>
                    <th>Base Rate</th>
                    <th>Status</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zones as $zone)
                <tr>
                    <td style="color:var(--text-3);font-size:.8rem">{{ $zone->id }}</td>
                    <td>
                        <div style="font-weight:600;font-size:.875rem">{{ $zone->name }}</div>
                        @if($zone->description ?? null)
                            <div style="font-size:.72rem;color:var(--text-3)">{{ $zone->description }}</div>
                        @endif
                    </td>
                    <td style="font-size:.82rem;color:var(--text-2)">
                        {{ $zone->regions ?? $zone->areas ?? '—' }}
                    </td>
                    <td style="font-weight:700;font-family:'Inter',sans-serif;color:var(--primary)">
                        @if(isset($zone->base_rate) || isset($zone->rates))
                            ৳{{ number_format($zone->base_rate ?? $zone->rates->min('rate') ?? 0, 0) }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($zone->is_active)
                            <span style="background:rgba(13,115,119,.1);color:var(--teal);padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Active</span>
                        @else
                            <span style="background:rgba(107,114,128,.1);color:#6B7280;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 table-row-actions">
                            <a href="{{ route('admin.shipping.edit', $zone->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-zone-{{ $zone->id }}" action="{{ route('admin.shipping.destroy', $zone->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-zone-{{ $zone->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-truck fs-2 d-block mb-2" style="opacity:.4"></i>No shipping zones configured.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
