@extends('layouts.admin')
@section('title', 'Reviews')
@section('breadcrumb')
<li class="breadcrumb-item active">Reviews</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-star me-2" style="color:var(--teal)"></i>Product Reviews</h4>
        <div class="page-header-sub">{{ $reviews->total() }} total reviews</div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Review List</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th style="width:100px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td style="font-size:.84rem;font-weight:600">{{ Str::limit($review->product->name ?? 'Deleted', 30) }}</td>
                    <td style="font-size:.84rem;color:var(--text-2)">{{ $review->user->name ?? 'Guest' }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"
                                   style="font-size:.75rem;color:{{ $i <= $review->rating ? 'var(--gold)' : 'var(--text-3)' }}"></i>
                            @endfor
                            <span style="font-size:.75rem;font-weight:700;margin-left:4px;color:var(--text-2)">{{ $review->rating }}/5</span>
                        </div>
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">{{ Str::limit($review->comment, 55) }}</td>
                    <td>
                        @if($review->is_approved)
                            <span style="background:rgba(16,185,129,.1);color:#059669;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Approved</span>
                        @else
                            <span style="background:rgba(245,158,11,.12);color:#D97706;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Pending</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $review->is_approved ? 'btn-warning' : 'btn-outline-primary' }}"
                                    title="{{ $review->is_approved ? 'Unapprove' : 'Approve' }}">
                                <i class="bi bi-{{ $review->is_approved ? 'x-circle' : 'check-circle' }} me-1"></i>
                                {{ $review->is_approved ? 'Hide' : 'Approve' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-star fs-2 d-block mb-2" style="opacity:.4"></i>No reviews yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
