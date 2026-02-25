@extends('layouts.admin')
@section('title', 'Categories')
@section('breadcrumb')
<li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-folder2-open me-2" style="color:var(--teal)"></i>Categories</h4>
        <div class="page-header-sub">{{ $categories->total() }} total categories</div>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Category
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>All Categories</span>
        <span style="font-size:.78rem;color:var(--text-3);font-weight:500">{{ $categories->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th style="width:60px">Image</th>
                    <th>Category Name</th>
                    <th>Parent</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="width:110px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td style="color:var(--text-3);font-size:.8rem">{{ $category->id }}</td>
                    <td>
                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}"
                                 style="width:42px;height:42px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border)">
                        @else
                            <div style="width:42px;height:42px;background:var(--surface-2);border-radius:8px;border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-3)">
                                <i class="bi bi-folder2"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.875rem;color:var(--text)">{{ $category->name }}</div>
                        @if($category->description)
                            <div style="font-size:.72rem;color:var(--text-3);margin-top:2px">{{ Str::limit($category->description, 50) }}</div>
                        @endif
                    </td>
                    <td>
                        @if($category->parent)
                            <span style="background:rgba(13,115,119,.1);color:var(--teal);padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600">
                                <i class="bi bi-arrow-return-right me-1"></i>{{ $category->parent->name }}
                            </span>
                        @else
                            <span style="color:var(--text-3);font-size:.8rem">Top Level</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:var(--text-3);font-family:monospace">{{ $category->slug }}</td>
                    <td>
                        <span style="font-weight:700;font-family:'Inter',sans-serif;color:var(--primary)">
                            {{ $category->products_count ?? $category->products()->count() }}
                        </span>
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">{{ $category->sort_order ?? 0 }}</td>
                    <td>
                        @if($category->is_active)
                            <span style="background:rgba(13,115,119,.1);color:var(--teal);padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Active</span>
                        @else
                            <span style="background:rgba(107,114,128,.1);color:#6B7280;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 table-row-actions">
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-cat-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                        onclick="confirmDelete('del-cat-{{ $category->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-folder2 fs-2 d-block mb-2" style="opacity:.4"></i>
                        No categories found.
                        <a href="{{ route('admin.categories.create') }}" class="d-block mt-2" style="color:var(--teal)">Add your first category →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
