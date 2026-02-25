@extends('layouts.admin')
@section('title', 'Products')
@section('breadcrumb')
<li class="breadcrumb-item active">Products</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.min.css">
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
    .dt-container .dt-paging .page-link:hover {
        background: var(--surface-2);
        color: var(--teal);
    }
    /* Header */
    #products-dt thead th {
        border-bottom: 2px solid var(--border) !important;
        font-size: .7rem !important;
        text-transform: uppercase !important;
        letter-spacing: .9px !important;
        color: var(--text-2) !important;
        font-weight: 700 !important;
        padding: 12px 14px !important;
        white-space: nowrap;
        background: var(--surface-2) !important;
    }
    #products-dt thead th.dt-orderable-asc,
    #products-dt thead th.dt-orderable-desc { cursor: pointer; }
    #products-dt thead th.dt-orderable-asc:hover,
    #products-dt thead th.dt-orderable-desc:hover { color: var(--teal) !important; }
    /* Rows */
    #products-dt tbody tr { transition: background .15s; }
    #products-dt tbody tr:hover td { background: var(--surface-2) !important; }
    #products-dt tbody td { padding: 10px 14px !important; vertical-align: middle !important; border-bottom: 1px solid var(--border) !important; }
    /* Processing overlay */
    #products-dt_processing {
        background: rgba(255,255,255,.92) !important;
        border: none !important;
        color: var(--teal) !important;
        font-size: .85rem !important;
        box-shadow: var(--shadow) !important;
        border-radius: 10px !important;
        padding: 16px 28px !important;
    }
    /* DT layout rows */
    .dt-container { padding: 0 !important; }
    .dt-layout-row { padding: 12px 20px !important; }
    .dt-layout-row:first-child { border-bottom: 1px solid var(--border); }
    .dt-layout-row:last-child  { border-top: 1px solid var(--border); }
    /* Responsive child rows */
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before {
        background: var(--teal) !important;
    }
    /* Mobile: stack DT controls */
    @media (max-width: 767px) {
        .dt-layout-row { flex-direction: column !important; align-items: flex-start !important; gap: 8px; }
        .dt-layout-cell { width: 100% !important; }
        .dt-container .dt-search,
        .dt-container .dt-length { width: 100% !important; }
        .dt-container .dt-search input,
        .dt-container .dt-length select { width: 100% !important; }
        #filter-bar { flex-direction: column !important; }
        #filter-bar select { width: 100% !important; }
    }
</style>
@endpush

@section('content')

{{-- ── Page Header ──────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h4><i class="bi bi-box-seam me-2" style="color:var(--teal)"></i>Products</h4>
        <div class="page-header-sub" id="dt-info-text">Loading…</div>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Product
    </a>
</div>

{{-- ── Server-side Filter Bar ───────────────────────── --}}
<div class="admin-card mb-4">
    <div class="card-body" style="padding:14px 20px">
        <div id="filter-bar" class="d-flex gap-2 flex-wrap align-items-center">
            <select id="filter-category" class="form-select" style="max-width:200px;height:38px;font-size:.85rem">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <select id="filter-status" class="form-select" style="max-width:160px;height:38px;font-size:.85rem">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Draft</option>
                <option value="deleted">Trashed</option>
            </select>
            <button id="btn-clear" class="btn btn-sm d-flex align-items-center gap-1"
                    style="height:38px;background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2);font-size:.83rem">
                <i class="bi bi-x-circle"></i> Clear
            </button>
        </div>
    </div>
</div>

{{-- ── DataTable Card ───────────────────────────────── --}}
<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Product List</span>
        <span id="dt-badge" class="badge" style="background:rgba(13,115,119,.12);color:var(--teal);font-size:.72rem"></span>
    </div>
    <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
        <table id="products-dt" class="table table-hover mb-0" style="width:100%;min-width:700px">
            <thead>
                <tr>
                    <th>Created At</th>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
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
    var table = $('#products-dt').DataTable({
        processing  : true,
        serverSide  : true,
        responsive  : {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        ajax: {
            url : '{{ route('admin.products.datatable') }}',
            type: 'GET',
            data: function (d) {
                d.category_id = $('#filter-category').val();
                d.status      = $('#filter-status').val();
            },
            error: function (xhr) {
                console.error('DataTable AJAX error:', xhr.status, xhr.responseText);
            }
        },
        columns: [
            { data: 'created_at', name: 'created_at', visible: false, searchable: false },
            { data: null, name: 'id', width: '50px', orderable: false, searchable: false, responsivePriority: 6,
              render: function(data, type, row, meta) {
                  return '<span style="color:var(--text-3);font-size:.8rem">' + (meta.row + meta.settings._iDisplayStart + 1) + '</span>';
              }
            },
            { data: 'image',    name: 'image',       orderable: false, searchable: false, width: '60px', responsivePriority: 4 },
            { data: 'name',     name: 'name',        responsivePriority: 1 },
            { data: 'category', name: 'category_id', responsivePriority: 5 },
            { data: 'price',    name: 'price',       width: '120px', responsivePriority: 3 },
            { data: 'stock',    name: 'stock',       width: '120px', responsivePriority: 4 },
            { data: 'status',   name: 'is_active',   width: '100px', responsivePriority: 3 },
            { data: 'actions',  name: 'actions',     orderable: false, searchable: false, width: '130px', responsivePriority: 2 }
        ],
        order      : [[0, 'desc']],
        pageLength : 25,
        lengthMenu : [[10, 25, 50, 100], ['10', '25', '50', '100']],
        language   : {
            processing  : '<span class="spinner-border spinner-border-sm me-2" style="color:var(--teal)"></span>Loading…',
            emptyTable  : '<div class="text-center py-5" style="color:var(--text-3)"><i class="bi bi-box-seam fs-2 d-block mb-2" style="opacity:.35"></i>No products found. <a href="{{ route('admin.products.create') }}" class="text-teal">Add one →</a></div>',
            zeroRecords : '<div class="text-center py-5" style="color:var(--text-3)"><i class="bi bi-search fs-2 d-block mb-2" style="opacity:.35"></i>No products match your filters</div>',
            info        : 'Showing _START_–_END_ of _TOTAL_ products',
            infoFiltered: '(filtered from _MAX_)',
            search      : '',
            searchPlaceholder: 'Search products…',
            lengthMenu  : '_MENU_ per page',
            paginate: { first:'«', last:'»', next:'›', previous:'‹' }
        },
        drawCallback: function (s) {
            var total = s.json ? s.json.recordsTotal : 0;
            $('#dt-info-text').text(total + ' product' + (total !== 1 ? 's' : '') + ' in catalog');
            $('#dt-badge').text(total);
        }
    });

    // Hook external filters
    var debounce;
    $('#filter-category, #filter-status').on('change', function () {
        clearTimeout(debounce);
        debounce = setTimeout(function () { table.draw(); }, 200);
    });
    $('#btn-clear').on('click', function () {
        $('#filter-category').val('');
        $('#filter-status').val('');
        table.search('').draw();
    });
});
</script>
@endpush
