@extends('layouts.admin')
@section('title', 'Orders')
@section('breadcrumb')
<li class="breadcrumb-item active">Orders</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.min.css">
<style>
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
    .dt-container .dt-info { color: var(--text-2) !important; font-size: .8rem !important; }
    .dt-container .dt-paging .page-link {
        border-radius: 6px !important; font-size: .8rem; color: var(--text-2);
        border-color: var(--border); background: var(--surface); margin: 0 2px;
    }
    .dt-container .dt-paging .page-item.active .page-link {
        background: var(--teal) !important; border-color: var(--teal) !important; color:#fff !important;
    }
    .dt-container .dt-paging .page-link:hover { background: var(--surface-2); color: var(--teal); }
    #orders-dt thead th {
        border-bottom: 2px solid var(--border) !important;
        font-size: .7rem !important; text-transform: uppercase !important;
        letter-spacing: .9px !important; color: var(--text-2) !important;
        font-weight: 700 !important; padding: 12px 14px !important;
        background: var(--surface-2) !important; white-space: nowrap;
    }
    #orders-dt tbody tr:hover td { background: var(--surface-2) !important; }
    #orders-dt tbody td { padding: 10px 14px !important; vertical-align: middle !important; border-bottom: 1px solid var(--border) !important; }
    .dt-container { padding: 0 !important; }
    .dt-layout-row { padding: 12px 20px !important; }
    .dt-layout-row:first-child { border-bottom: 1px solid var(--border); }
    .dt-layout-row:last-child  { border-top: 1px solid var(--border); }
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before { background: var(--teal) !important; }

    /* Order status badge styles */
    .ord-badge {
        padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; white-space: nowrap;
    }

    @media (max-width: 767px) {
        .dt-layout-row { flex-direction: column !important; align-items: flex-start !important; gap: 8px; }
        .dt-layout-cell { width: 100% !important; }
        .dt-container .dt-search input,
        .dt-container .dt-length select { width: 100% !important; }
        #filter-bar { flex-direction: column !important; }
        #filter-bar .filter-group { width: 100% !important; }
        #filter-bar select,
        #filter-bar input[type=date] { width: 100% !important; }
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h4><i class="bi bi-receipt me-2" style="color:var(--teal)"></i>Orders</h4>
        <div class="page-header-sub" id="dt-info-text">Loading…</div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="admin-card mb-4">
    <div class="card-body" style="padding:14px 20px">
        <div id="filter-bar" class="d-flex gap-2 flex-wrap align-items-center">
            {{-- Order status --}}
            <div class="filter-group">
                <select id="filter-status" class="form-select" style="min-width:140px;height:38px;font-size:.85rem">
                    <option value="">All Status</option>
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Payment status --}}
            <div class="filter-group">
                <select id="filter-payment" class="form-select" style="min-width:150px;height:38px;font-size:.85rem">
                    <option value="">All Payment</option>
                    <option value="pending">Unpaid</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            {{-- Payment method --}}
            <div class="filter-group">
                <select id="filter-method" class="form-select" style="min-width:150px;height:38px;font-size:.85rem">
                    <option value="">All Methods</option>
                    <option value="cod">Cash on Delivery</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="online">Online</option>
                </select>
            </div>
            {{-- Date range --}}
            <div class="filter-group d-flex gap-1 align-items-center">
                <input type="date" id="filter-from" class="form-control" style="height:38px;font-size:.83rem;min-width:140px" placeholder="From">
                <span class="text-muted" style="font-size:.8rem">–</span>
                <input type="date" id="filter-to" class="form-control" style="height:38px;font-size:.83rem;min-width:140px" placeholder="To">
            </div>
            <button id="btn-clear" class="btn btn-sm d-flex align-items-center gap-1"
                    style="height:38px;background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2);font-size:.83rem">
                <i class="bi bi-x-circle"></i> Clear
            </button>
        </div>
    </div>
</div>

{{-- DataTable Card --}}
<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Order List</span>
        <span id="dt-badge" class="badge" style="background:rgba(13,115,119,.12);color:var(--teal);font-size:.72rem"></span>
    </div>
    <div class="table-responsive" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
        <table id="orders-dt" class="table table-hover mb-0" style="width:100%;min-width:800px">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Method</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
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
    var table = $('#orders-dt').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
            details: { type: 'column', target: 'tr' }
        },
        ajax: {
            url : '{{ route('admin.orders.datatable') }}',
            type: 'GET',
            data: function (d) {
                d.status  = $('#filter-status').val();
                d.payment = $('#filter-payment').val();
                d.method  = $('#filter-method').val();
                d.from    = $('#filter-from').val();
                d.to      = $('#filter-to').val();
            },
            error: function (xhr) { console.error('DT error:', xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'id',       name: 'id',           width: '50px', className: 'text-muted',              responsivePriority: 9 },
            { data: 'number',   name: 'order_number',  width: '130px',                                      responsivePriority: 1 },
            { data: 'customer', name: 'shipping_name',                                                       responsivePriority: 2 },
            { data: 'items',    name: 'id',            orderable: false, searchable: false, width: '80px',   responsivePriority: 7 },
            { data: 'total',    name: 'total',         width: '110px',                                       responsivePriority: 3 },
            { data: 'method',   name: 'payment_method',width: '120px',                                       responsivePriority: 6 },
            { data: 'payment',  name: 'payment_status',width: '100px',                                       responsivePriority: 4 },
            { data: 'status',   name: 'status',        width: '110px',                                       responsivePriority: 3 },
            { data: 'date',     name: 'created_at',    width: '100px',                                       responsivePriority: 5 },
            { data: 'actions',  name: 'actions',       orderable: false, searchable: false, width: '120px',  responsivePriority: 1 },
        ],
        order     : [[0, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], ['10', '25', '50', '100']],
        language  : {
            processing       : '<span class="spinner-border spinner-border-sm me-2" style="color:var(--teal)"></span>Loading…',
            emptyTable       : '<div class="text-center py-5" style="color:var(--text-3)"><i class="bi bi-receipt fs-2 d-block mb-2" style="opacity:.35"></i>No orders found</div>',
            zeroRecords      : '<div class="text-center py-5" style="color:var(--text-3)"><i class="bi bi-search fs-2 d-block mb-2" style="opacity:.35"></i>No orders match your filters</div>',
            info             : 'Showing _START_–_END_ of _TOTAL_ orders',
            infoFiltered     : '(filtered from _MAX_)',
            search           : '',
            searchPlaceholder: 'Search order #, name, phone…',
            lengthMenu       : '_MENU_ per page',
            paginate         : { first:'«', last:'»', next:'›', previous:'‹' }
        },
        drawCallback: function (s) {
            var total = s.json ? s.json.recordsTotal : 0;
            $('#dt-info-text').text(total + ' total order' + (total !== 1 ? 's' : ''));
            $('#dt-badge').text(total);
        }
    });

    // External filter change → redraw
    var debounce;
    $('#filter-status, #filter-payment, #filter-method').on('change', function () {
        clearTimeout(debounce);
        debounce = setTimeout(function () { table.draw(); }, 200);
    });
    $('#filter-from, #filter-to').on('change', function () {
        clearTimeout(debounce);
        debounce = setTimeout(function () { table.draw(); }, 400);
    });
    $('#btn-clear').on('click', function () {
        $('#filter-status, #filter-payment, #filter-method').val('');
        $('#filter-from, #filter-to').val('');
        table.search('').draw();
    });
});
</script>
@endpush
