@extends('layouts.admin')
@section('title', 'Customers')
@section('breadcrumb')
<li class="breadcrumb-item active">Customers</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.min.css">
<style>
    /* ── DataTables custom theme integration ─────────────── */
    .dt-container .dt-search input,
    .dt-container .dt-length select {
        background: var(--surface-2) !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 8px !important;
        color: var(--text) !important;
        font-size: .83rem !important;
        padding: 6px 12px !important;
        height: 36px !important;
        box-shadow: none !important;
    }
    .dt-container .dt-search label, 
    .dt-container .dt-length label,
    .dt-container .dt-info {
        color: var(--text-2) !important;
        font-size: .8rem !important;
    }
    .dt-container .dt-search { display: flex; align-items: center; gap: 8px; }
    .dt-container .dt-search::before {
        content: '\F52A';
        font-family: 'bootstrap-icons';
        color: var(--text-3);
        font-size: 1rem;
        margin-right: -6px;
    }
    /* Pagination */
    .dt-container .dt-paging .page-link {
        border-radius: 6px !important;
        font-size: .8rem;
        color: var(--text-2);
        border-color: var(--border);
        background: var(--surface);
        margin: 0 2px;
    }
    .dt-container .dt-paging .page-item.active .page-link {
        background: var(--teal) !important;
        border-color: var(--teal) !important;
        color: #fff !important;
    }
    /* Header */
    #customers-dt thead th {
        border-bottom: 2px solid var(--border) !important;
        font-size: .7rem !important;
        text-transform: uppercase !important;
        letter-spacing: .9px !important;
        color: var(--text-2) !important;
        font-weight: 700 !important;
        padding: 12px 14px !important;
        background: var(--surface-2) !important;
    }
    /* Rows */
    #customers-dt tbody tr:hover td { background: var(--surface-2) !important; }
    #customers-dt tbody td { padding: 10px 14px !important; vertical-align: middle !important; border-bottom: 1px solid var(--border) !important; }
    
    /* Layout */
    .dt-layout-row { padding: 12px 20px !important; }
    .dt-layout-row:first-child { border-bottom: 1px solid var(--border); }
    .dt-layout-row:last-child  { border-top: 1px solid var(--border); }

    @media (max-width: 767px) {
        .dt-layout-row { flex-direction: column !important; align-items: flex-start !important; gap: 8px; }
        .dt-layout-cell { width: 100% !important; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-people me-2" style="color:var(--teal)"></i>Customers</h4>
        <div class="page-header-sub" id="dt-info-text">Loading…</div>
    </div>
</div>

{{-- ── Filter Bar ───────────────────────── --}}
<div class="admin-card mb-4">
    <div class="card-body" style="padding:14px 20px">
        <div id="filter-bar" class="d-flex gap-2 flex-wrap align-items-center">
            <select id="filter-status" class="form-select" style="max-width:160px;height:38px;font-size:.85rem">
                <option value="">All Status</option>
                <option value="active">Active Members</option>
                <option value="banned">Banned Members</option>
            </select>
            <button id="btn-clear" class="btn btn-sm" style="height:38px;background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2);font-size:.83rem">
                <i class="bi bi-x-circle me-1"></i> Clear
            </button>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Customer Management</span>
        <span id="dt-badge" class="badge" style="background:rgba(13,115,119,.12);color:var(--teal);font-size:.72rem"></span>
    </div>
    <div class="table-responsive">
        <table id="customers-dt" class="table table-hover mb-0" style="width:100%">
            <thead>
                <tr>
                    <th>Created Raw</th>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Orders</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th style="width:80px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.min.js"></script>
<script>
$(function () {
    var table = $('#customers-dt').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url : '{{ route('admin.customers.datatable') }}',
            type: 'GET',
            data: function (d) {
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'created_at_raw', name: 'created_at', visible: false, searchable: false },
            { data: 'id',      name: 'id',      orderable: false, searchable: false },
            { data: 'name',    name: 'name',    responsivePriority: 1 },
            { data: 'email',   name: 'email',   responsivePriority: 3 },
            { data: 'orders',  name: 'id',      searchable: false, responsivePriority: 4 },
            { data: 'joined',  name: 'created_at', responsivePriority: 5 },
            { data: 'status',  name: 'id',      searchable: false, responsivePriority: 2 },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, responsivePriority: 2 }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        language: {
            search: '',
            searchPlaceholder: 'Search by name, email or phone…',
            info: 'Showing _START_ to _END_ of _TOTAL_ customers',
            paginate: { next: '›', previous: '‹' }
        },
        drawCallback: function (s) {
            var total = s.json ? s.json.recordsTotal : 0;
            $('#dt-info-text').text(total + ' registered customers');
            $('#dt-badge').text(total);
        }
    });

    $('#filter-status').on('change', function () { table.draw(); });
    $('#btn-clear').on('click', function () {
        $('#filter-status').val('');
        table.search('').draw();
    });
});
</script>
@endpush
