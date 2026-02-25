@extends('layouts.admin')
@section('title', 'Sliders / Banners')
@section('breadcrumb')
<li class="breadcrumb-item active">Sliders</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-image me-2" style="color:var(--teal)"></i>Sliders & Banners</h4>
        <div class="page-header-sub">Hero slider management</div>
    </div>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Slider
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-images me-2" style="color:var(--teal)"></i>Slider List</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:110px">Preview</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="width:110px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sliders as $slider)
                <tr>
                    <td>
                        <img src="{{ asset('storage/'.$slider->image) }}"
                             style="width:100px;height:58px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border)"
                             alt="{{ $slider->title }}">
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.875rem">{{ $slider->title ?? '—' }}</div>
                        @if($slider->highlight ?? $slider->button_text ?? null)
                            <div style="font-size:.72rem;color:var(--teal);margin-top:2px">
                                <i class="bi bi-cursor me-1"></i>{{ $slider->button_text ?? $slider->highlight }}
                            </div>
                        @endif
                    </td>
                    <td style="font-size:.82rem;color:var(--text-2)">{{ Str::limit($slider->subtitle, 50) ?? '—' }}</td>
                    <td style="font-size:.84rem;color:var(--text-2)">{{ $slider->sort_order }}</td>
                    <td>
                        @if($slider->is_active)
                            <span style="background:rgba(13,115,119,.1);color:var(--teal);padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Active</span>
                        @else
                            <span style="background:rgba(107,114,128,.1);color:#6B7280;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 table-row-actions">
                            <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-sl-{{ $slider->id }}" action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-sl-{{ $slider->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-image fs-2 d-block mb-2" style="opacity:.4"></i>No sliders added yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
