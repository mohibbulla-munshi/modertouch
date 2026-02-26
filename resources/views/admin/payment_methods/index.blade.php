@extends('layouts.admin')
@section('title', 'Payment Methods')
@section('breadcrumb')
<li class="breadcrumb-item active">Payment Methods</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.min.css">
<style>
    .dataTables_wrapper .row { margin-bottom: 15px; }
    .dataTables_filter input { width: 250px !important; display: inline-block; }
    .page-item.active .page-link { background-color: var(--teal); border-color: var(--teal); }
    .page-link { color: var(--teal); }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="bi bi-wallet2 me-2" style="color:var(--teal)"></i>Payment Methods</h4>
        <div class="page-header-sub text-muted">Manage available payment options for checkout</div>
    </div>
    <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Method
    </a>
</div>

<div class="admin-card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="payments-dt" class="table table-hover align-middle mb-0 w-100">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">Method Name</th>
                        <th>Type Code</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($methods as $method)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $method->name }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $method->type }}</span></td>
                        <td><small class="text-muted">{{ Str::limit($method->description, 50) }}</small></td>
                        <td>
                            @if($method->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-pay-{{ $method->id }}" action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST" class="d-inline-block">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="confirmDelete('del-pay-{{ $method->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#payments-dt').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search methods..."
            },
            columnDefs: [
                { orderable: false, targets: [4] } // Disable sorting on actions column
            ]
        });
    });
</script>
@endpush

@endsection
