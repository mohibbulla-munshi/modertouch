@extends('layouts.admin')
@section('title', 'Return Request Detail')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.returns.index') }}">Returns</a></li>
<li class="breadcrumb-item active">RMA #{{ $return->id }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-info-circle me-2" style="color:var(--teal)"></i>RMA #{{ $return->id }} Details</h4>
        <div class="page-header-sub">Requested on {{ $return->created_at->format('d M Y, H:i') }}</div>
    </div>
    <a href="{{ route('admin.returns.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to List
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="card-header">Return Case Information</div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <label class="small text-muted text-uppercase fw-700 ls-1">Customer</label>
                        <div class="fw-600 fs-5">{{ $return->user->name }}</div>
                        <div class="text-muted">{{ $return->user->email }}</div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <label class="small text-muted text-uppercase fw-700 ls-1">Order Ref</label>
                        <div class="fw-600 fs-5">Order #{{ $return->order_id }}</div>
                        <a href="{{ route('admin.orders.show', $return->order_id) }}" target="_blank" class="small">View Order Details <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                    </div>
                </div>

                <div class="mb-4 p-3 border rounded bg-light">
                    <label class="small text-muted text-uppercase fw-700 ls-1">Product Requested for Return</label>
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <img src="{{ $return->product->featured_image ? asset('storage/'.$return->product->featured_image) : 'https://via.placeholder.com/80' }}" class="rounded shadow-sm" style="width:70px;height:70px;object-fit:cover">
                        <div>
                            <div class="fw-700">{{ $return->product->name }}</div>
                            <div class="small">Quantity: <strong>{{ $return->quantity }}</strong></div>
                            <div class="small">Item Price: <strong>৳{{ number_format($return->product->price, 0) }}</strong></div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="small text-muted text-uppercase fw-700 ls-1">Reason for Return</label>
                    <div class="p-3 border rounded mt-1 bg-white" style="font-style: italic;">
                        "{{ $return->reason }}"
                    </div>
                </div>

                @if($return->images && count($return->images) > 0)
                <div>
                    <label class="small text-muted text-uppercase fw-700 ls-1">Evidence Photos ({{ count($return->images) }})</label>
                    <div class="row g-2 mt-1">
                        @foreach($return->images as $img)
                        <div class="col-3">
                            <a href="{{ asset('storage/'.$img) }}" target="_blank">
                                <img src="{{ asset('storage/'.$img) }}" class="img-fluid rounded border shadow-sm" style="aspect-ratio:1;object-fit:cover">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card mb-4" style="border-color:var(--teal)">
            <div class="card-header" style="background:rgba(13,115,119,.08); color:var(--teal)">
                Process Request
            </div>
            <div class="card-body">
                <form action="{{ route('admin.returns.update', $return->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <select name="status" class="form-select fw-600">
                            <option value="pending" {{ $return->status == 'pending' ? 'selected' : '' }}>🕒 PENDING REVIEW</option>
                            <option value="approved" {{ $return->status == 'approved' ? 'selected' : '' }}>✅ APPROVED (Await Return)</option>
                            <option value="rejected" {{ $return->status == 'rejected' ? 'selected' : '' }}>❌ REJECTED</option>
                            <option value="received" {{ $return->status == 'received' ? 'selected' : '' }}>📦 RECEIVED & RESTOCKED</option>
                            <option value="refunded" {{ $return->status == 'refunded' ? 'selected' : '' }}>💰 REFUNDED TO WALLET</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Refund Amount (৳)</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" step="0.01" name="refund_amount" class="form-control fw-700" value="{{ $return->refund_amount > 0 ? $return->refund_amount : $return->product->price * $return->quantity }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Admin Internal Notes</label>
                        <textarea name="admin_note" class="form-control" rows="4" placeholder="Mention inspection results, reason for rejection, etc...">{{ $return->admin_note }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-700 shadow-sm">
                        Apply Status Update
                    </button>
                </form>

                <div class="mt-4 p-3 border rounded" style="font-size: .85rem; background: rgba(59,130,246,.05); border-color: rgba(59,130,246,.2) !important;">
                    <div class="fw-800 mb-2 text-primary"><i class="bi bi-gear-fill me-1"></i> System Automation Logic:</div>
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-box-seam text-success mt-1"></i>
                        <div>
                            <strong class="text-success">RECEIVED:</strong> Automatically **increases product stock** ({{ $return->quantity }} units) and logs an entry in the **Inventory Ledger**.
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-wallet2 text-teal mt-1"></i>
                        <div>
                            <strong class="text-teal">REFUNDED:</strong> Automatically **credits the User's Wallet** and creates a **Financial Transaction** audit log.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
