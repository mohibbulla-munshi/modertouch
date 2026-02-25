@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Roles & Permissions</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h4 class="mb-1">Roles & Permissions</h4>
        <div class="page-header-sub">Manage user roles and configure module access</div>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius:12px;">
        <i class="bi bi-shield-plus me-1"></i> Create Role
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-lock text-primary me-2 fs-5"></i>
            <span>All Roles</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:25%">ROLE NAME</th>
                        <th>PERMISSIONS COUNT</th>
                        <th>PERMISSIONS PREVIEW</th>
                        <th class="text-end" style="width:120px">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $role->name }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-bold" style="font-size:.8rem;">
                                    {{ $role->permissions->count() }} Modules
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($role->permissions->take(5) as $permission)
                                        <span class="badge bg-light text-secondary border" style="font-weight:600; font-size:.7rem;">
                                            {{ str_replace('manage_', '', $permission->name) }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->count() > 5)
                                        <span class="badge bg-light text-secondary border" style="font-weight:600; font-size:.7rem;">+{{ $role->permissions->count() - 5 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end table-row-actions">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-light" title="Edit permissions">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form id="delete-role-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light text-danger" onclick="confirmDelete('delete-role-{{ $role->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-x fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                No custom roles found.<br>
                                <small>Super Admin role is hidden from this list.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
