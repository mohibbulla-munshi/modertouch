@extends('layouts.admin')
@section('title', 'Customer Details')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
<li class="breadcrumb-item active">View Customer</li>
@endsection
@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-person me-2" style="color:var(--teal)"></i>Customer Profile</h4>
        <div class="page-header-sub">{{ $user->name }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        @if(!$user->is_banned)
            <button type="button" class="btn btn-sm btn-danger px-3 py-2" data-bs-toggle="modal" data-bs-target="#banModal">
                <i class="bi bi-slash-circle me-1"></i>Ban User
            </button>
        @else
            <form action="{{ route('admin.customers.unban', ['user' => $user->id]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-success px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i>Unban User
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Customer Info Card -->
    <div class="col-lg-4">
        <div class="admin-card text-center mb-4">
            <div class="card-body py-5">
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));color:#fff;font-size:2rem;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="mb-1" style="font-weight:700">{{ $user->name }}</h5>
                <div style="color:var(--text-3);font-size:.9rem;margin-bottom:12px">{{ $user->email }}</div>
                
                @if($user->is_banned)
                    <div class="d-inline-flex px-3 py-1 mb-3" style="background:rgba(239,68,68,.1);color:#DC2626;border-radius:20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px">
                        Banned
                    </div>
                    <div style="font-size:.85rem;color:var(--text-2);background:var(--surface-2);padding:10px;border-radius:8px;border:1px solid var(--border)">
                        <strong>Reason:</strong> {{ $user->ban_reason }}
                        @if($user->banned_at)
                            <div style="font-size:.75rem;margin-top:4px;color:var(--text-3)">Banned on {{ Carbon\Carbon::parse($user->banned_at)->format('d M Y') }}</div>
                        @endif
                    </div>
                @else
                    <div class="d-inline-flex px-3 py-1" style="background:rgba(16,185,129,.1);color:#059669;border-radius:20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px">
                        Active Account
                    </div>
                @endif
                
                <hr style="margin:24px 0;opacity:.1">
                <div class="d-flex justify-content-between text-start mb-2" style="font-size:.85rem">
                    <span style="color:var(--text-3)">Joined On</span>
                    <span style="font-weight:600">{{ $user->created_at ? $user->created_at->format('d M, Y') : 'Unknown' }}</span>
                </div>
                <div class="d-flex justify-content-between text-start mb-2" style="font-size:.85rem">
                    <span style="color:var(--text-3)">Total Orders</span>
                    <span style="font-weight:600">{{ $user->orders->count() }}</span>
                </div>
                <div class="d-flex justify-content-between text-start" style="font-size:.85rem">
                    <span style="color:var(--text-3)">Total Spent</span>
                    <span style="font-weight:700;color:var(--primary);font-family:'Inter',sans-serif">৳{{ number_format($user->orders->where('payment_status', 'paid')->sum('total_amount'), 0) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Action: Send Email -->
        <div class="admin-card">
            <div class="card-header"><i class="bi bi-envelope me-2" style="color:var(--teal)"></i>Send Email</div>
            <div class="card-body">
                <form action="{{ route('admin.customers.email', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem">Subject</label>
                        <input type="text" name="subject" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem">Message</label>
                        <textarea name="body" class="form-control form-control-sm" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-send me-1"></i>Send Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Content Tabs -->
    <div class="col-lg-8">
        <ul class="nav nav-tabs admin-tabs mb-4" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#orders" type="button">
                    <i class="bi bi-bag"></i> Order History
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#addresses" type="button">
                    <i class="bi bi-geo-alt"></i> Addresses
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                    <i class="bi bi-star"></i> Reviews
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Orders Tab -->
            <div class="tab-pane fade show active" id="orders">
                <div class="admin-card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->orders as $order)
                                <tr>
                                    <td style="font-family:monospace;font-weight:700">#{{ $order->order_number }}</td>
                                    <td style="font-size:.84rem;color:var(--text-3)">{{ $order->created_at ? $order->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td style="font-weight:700;color:var(--primary);font-family:'Inter',sans-serif">৳{{ number_format($order->total_amount, 0) }}</td>
                                    <td>
                                        @php
                                            $sColor = match($order->status){
                                                'completed' => ['bg'=>'rgba(16,185,129,.1)','c'=>'#059669'],
                                                'pending' => ['bg'=>'rgba(245,158,11,.1)','c'=>'#D97706'],
                                                'cancelled' => ['bg'=>'rgba(239,68,68,.1)','c'=>'#DC2626'],
                                                default => ['bg'=>'var(--surface-2)','c'=>'var(--text-2)']
                                            };
                                        @endphp
                                        <span style="background:{{ $sColor['bg'] }};color:{{ $sColor['c'] }};padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5" style="color:var(--text-3)">
                                        <i class="bi bi-bag d-block mb-2" style="font-size:1.5rem;opacity:.4"></i>No orders placed yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Addresses Tab -->
            <div class="tab-pane fade" id="addresses">
                <div class="row g-3">
                    @forelse($user->addresses as $address)
                    <div class="col-md-6">
                        <div class="admin-card h-100" style="border:{{ $address->is_default ? '1.5px solid var(--teal)' : '1px solid var(--border)' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--text-3);letter-spacing:.5px">
                                        <i class="bi bi-{{ $address->type === 'home' ? 'house' : 'building' }} me-1"></i>{{ $address->type }}
                                    </div>
                                    @if($address->is_default)
                                        <span style="background:var(--teal);color:#fff;padding:2px 8px;border-radius:12px;font-size:.7rem;font-weight:700">Default</span>
                                    @endif
                                </div>
                                <div style="font-weight:600;margin-bottom:4px">{{ $address->name }}</div>
                                <div style="font-size:.85rem;color:var(--text-3);margin-bottom:2px">{{ $address->phone }}</div>
                                <div style="font-size:.85rem;color:var(--text-2);line-height:1.5">
                                    {{ $address->street_address }}<br>
                                    @if($address->area) {{ $address->area }}, @endif
                                    {{ $address->city }} @if($address->postal_code) - {{ $address->postal_code }} @endif<br>
                                    @if($address->region) {{ $address->region }} @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5" style="color:var(--text-3);background:var(--surface);border-radius:var(--radius);border:1px dashed var(--border)">
                        <i class="bi bi-geo-alt d-block mb-2" style="font-size:1.5rem;opacity:.4"></i>No saved addresses found.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews">
                <div class="admin-card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                @forelse($user->reviews as $review)
                                <tr>
                                    <td style="padding:16px 20px">
                                        <div class="d-flex align-items-center mb-2">
                                            @if($review->product && $review->product->primary_image)
                                                <img src="{{ asset('storage/'.$review->product->primary_image) }}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid var(--border);margin-right:12px">
                                            @endif
                                            <div>
                                                <div style="font-size:.85rem;font-weight:600;color:var(--text)">{{ $review->product ? $review->product->name : 'Unknown Product' }}</div>
                                                <div style="color:#F59E0B;font-size:.75rem">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="ms-auto" style="font-size:.75rem;color:var(--text-3)">{{ $review->created_at ? $review->created_at->diffForHumans() : '' }}</div>
                                        </div>
                                        <div style="font-size:.85rem;color:var(--text-2);background:var(--surface-2);padding:10px;border-radius:8px;border:1px solid var(--border)">
                                            "{{ $review->comment }}"
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center py-5" style="color:var(--text-3);border-bottom:none">
                                        <i class="bi bi-star d-block mb-2" style="font-size:1.5rem;opacity:.4"></i>No product reviews yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ban User Modal -->
<div class="modal fade" id="banModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border:none;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.1)">
            <form action="{{ route('admin.customers.ban', ['user' => $user->id]) }}" method="POST">
                @csrf
                <div class="modal-header" style="border-bottom:1px solid var(--border);background:rgba(239,68,68,.05)">
                    <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Ban Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p style="font-size:.9rem;color:var(--text-2);margin-bottom:16px">
                        Are you sure you want to ban <strong>{{ $user->name }}</strong>? They will be unable to log in and make purchases.
                    </p>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600">Reason for ban <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="ban_reason" rows="3" required placeholder="Provide a detailed reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border)">
                    <button type="button" class="btn btn-secondary" style="background:var(--surface-2);color:var(--text);border:1px solid var(--border)" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Confirm Ban</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modern Tabs */
.admin-tabs { border-bottom: 2px solid var(--border); margin-bottom: 24px; padding: 0; }
.admin-tabs .nav-item { margin-bottom: -2px; }
.admin-tabs .nav-link { border: none !important; color: var(--text-3); font-weight: 600; font-size: .9rem; padding: 12px 20px; transition: all 0.2s ease; background: transparent; position: relative; }
.admin-tabs .nav-link:hover { color: var(--teal); }
.admin-tabs .nav-link.active { color: var(--teal); background: transparent; }
.admin-tabs .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--teal); border-radius: 2px 2px 0 0; }
.admin-tabs .nav-link i { margin-right: 6px; }
</style>
@endsection
