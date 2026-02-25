@extends('layouts.admin')
@section('title', 'Inquiries')
@section('breadcrumb')
<li class="breadcrumb-item active">Inquiries</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-chat-dots me-2" style="color:var(--teal)"></i>Customer Inquiries</h4>
        <div class="page-header-sub">{{ $inquiries->total() }} total inquiries</div>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Inquiry List</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width:80px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inquiry)
                <tr style="{{ !$inquiry->is_read ? 'background:rgba(13,115,119,.03)' : '' }}">
                    <td>
                        <div style="font-weight:{{ $inquiry->is_read ? '500' : '700' }};font-size:.875rem">
                            {{ $inquiry->name }}
                        </div>
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">{{ $inquiry->email }}</td>
                    <td style="font-size:.84rem;color:var(--text);">{{ Str::limit($inquiry->subject, 45) }}</td>
                    <td>
                        @if(!$inquiry->is_read)
                            <span style="background:rgba(240,165,0,.15);color:#B45309;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">
                                <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle"></i>New
                            </span>
                        @else
                            <span style="background:rgba(107,114,128,.1);color:#6B7280;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Read</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:var(--text-2);white-space:nowrap">
                        {{ $inquiry->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-chat-dots fs-2 d-block mb-2" style="opacity:.4"></i>No inquiries yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inquiries->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $inquiries->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
