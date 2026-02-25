@extends('layouts.admin')
@section('title', 'Users & Roles')
@section('breadcrumb')
<li class="breadcrumb-item active">Admin Users</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-person-gear me-2" style="color:var(--teal)"></i>System Users</h4>
        <div class="page-header-sub">Admin panel access management</div>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add User
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2" style="color:var(--teal)"></i>User List</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th style="width:100px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.875rem">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <div style="font-size:.68rem;color:var(--teal);font-weight:600">You</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">{{ $user->email }}</td>
                    <td>
                        @php
                            $roleName = $user->roles->first()?->name ?? 'super_admin';
                            $roleName = $user->hasRole('Super Admin') ? 'Super Admin' : $roleName;
                            
                            $roleColor = match(strtolower($roleName)) {
                                'super admin', 'super_admin' => ['bg'=>'rgba(139,92,246,.12)','c'=>'#7C3AED'],
                                'admin' => ['bg'=>'rgba(13,115,119,.1)','c'=>'var(--teal)'],
                                default => ['bg'=>'rgba(107,114,128,.1)','c'=>'#6B7280'],
                            };
                        @endphp
                        <span style="background:{{ $roleColor['bg'] }};color:{{ $roleColor['c'] }};padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">
                            {{ ucfirst($roleName) }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;color:var(--text-2)">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1 table-row-actions">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form id="del-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-user-{{ $user->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-person-gear fs-2 d-block mb-2" style="opacity:.4"></i>No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
