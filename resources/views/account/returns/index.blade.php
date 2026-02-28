@extends('layouts.app')
@section('title', 'My Return Requests')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            @include('account._sidebar')
        </div>
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-700 mb-4 text-primary"><i class="bi bi-arrow-counterclockwise me-2"></i>Return Requests (RMA)</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>RMA ID</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $ret)
                                <tr>
                                    <td>#{{ $ret->id }}</td>
                                    <td>
                                        <div class="fw-600">{{ $ret->product->name }}</div>
                                        <div class="small text-muted">Order #{{ $ret->order_id }}</div>
                                    </td>
                                    <td>
                                        <span class="badge @if($ret->status == 'pending') bg-warning-subtle text-warning @elseif($ret->status == 'refunded') bg-success-subtle text-success @elseif($ret->status == 'rejected') bg-danger-subtle text-danger @else bg-primary-subtle text-primary @endif px-3 rounded-pill text-capitalize">
                                            {{ $ret->status }}
                                        </span>
                                    </td>
                                    <td class="small">
                                        {{ $ret->created_at->format('d M Y') }}
                                        @if($ret->admin_note)
                                        <div class="mt-2 p-2 bg-light border-start border-3 border-teal rounded-end small shadow-sm" style="font-style: italic; max-width: 250px;">
                                            <i class="bi bi-chat-left-text me-1 text-teal"></i> {{ $ret->admin_note }}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No return requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $returns->links() }}
                    </div>

                    <div class="mt-5 p-4 bg-light rounded-4 border border-dashed border-primary">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <h5 class="fw-700 mb-2">Need to return something else?</h5>
                                <p class="text-muted mb-0">Go to your <a href="{{ route('account.orders') }}" class="fw-600">Order History</a> and click on 'Request Return' for the specific item.</p>
                            </div>
                            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('account.orders') }}" class="btn btn-primary rounded-pill px-4">My Orders</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
