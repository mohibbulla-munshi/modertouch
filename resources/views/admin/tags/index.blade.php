@extends('layouts.admin')
@section('title', 'Tags')
@section('breadcrumb')
<li class="breadcrumb-item active">Tags</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-tags me-2" style="color:var(--teal)"></i>Tags</h4>
        <div class="page-header-sub">{{ $tags->total() }} product tags</div>
    </div>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Tag
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Tag List</span>
        <span style="font-size:.78rem;color:var(--text-3)">{{ $tags->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Tag Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                <tr>
                    <td style="color:var(--text-3);font-size:.8rem">{{ $tag->id }}</td>
                    <td>
                        <span style="background:rgba(13,115,119,.08);color:var(--teal);padding:4px 12px;border-radius:20px;font-size:.8rem;font-weight:600;border:1px solid rgba(13,115,119,.2)">
                            <i class="bi bi-hash me-1"></i>{{ $tag->name }}
                        </span>
                    </td>
                    <td style="font-family:monospace;font-size:.78rem;color:var(--text-3)">{{ $tag->slug }}</td>
                    <td style="font-weight:700;color:var(--primary);font-family:'Inter',sans-serif">
                        {{ $tag->products_count ?? $tag->products()->count() }}
                    </td>
                    <td>
                        <div class="d-flex gap-1 table-row-actions">
                            <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-tag-{{ $tag->id }}" action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-tag-{{ $tag->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-tags fs-2 d-block mb-2" style="opacity:.4"></i>No tags yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tags->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $tags->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
