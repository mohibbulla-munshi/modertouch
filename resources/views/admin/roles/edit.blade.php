@extends('layouts.admin')

@section('title', 'Edit Role')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit - {{ $role->name }}</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 text-primary"><i class="bi bi-shield-check me-2"></i>Edit Role</h4>
        <div class="page-header-sub text-muted">Modify permissions for {{ $role->name }}</div>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius:12px;">
        <i class="bi bi-arrow-left me-1"></i> Back to Roles
    </a>
</div>

<form action="{{ route('admin.roles.update', $role) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Role Name Section -->
        <div class="col-12 col-lg-4">
            <div class="admin-card text-center p-4">
                <div class="mb-4 mt-2">
                    <div class="d-inline-flex justify-content-center align-items-center bg-primary text-white mb-3" 
                         style="width:80px; height:80px; border-radius:24px; box-shadow:0 8px 24px rgba(26,58,92,0.2);">
                        <i class="bi bi-person-gear fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Role Details</h5>
                    <p class="text-muted small">Update the role's identity.</p>
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label text-secondary fw-bold" style="font-size:0.8rem; letter-spacing:0.5px;">ROLE NAME</label>
                    <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           value="{{ old('name', $role->name) }}" required
                           style="border-radius:12px; font-size:1rem; font-weight:500;">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="col-12 col-lg-8">
            <div class="admin-card h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-key text-primary me-2 fs-5"></i>
                        <span class="fs-6 fw-bold text-dark">Module Access Permissions</span>
                    </div>
                    <button type="button" id="selectAllBtn" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                        Select All
                    </button>
                </div>
                
                <div class="card-body p-4 bg-light bg-opacity-50">
                    <div class="row g-3">
                        @foreach($permissions as $permission)
                            <div class="col-12 col-md-6 col-xl-4">
                                @php $isChecked = in_array($permission->name, $rolePermissions); @endphp
                                <label class="w-100 p-3 bg-white border rounded position-relative" style="cursor:pointer; transition:all 0.2s; border-radius:12px !important;" onmouseover="this.style.borderColor='var(--teal)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'" onmouseout="if(!this.querySelector('input').checked){this.style.borderColor='var(--border)'; this.style.boxShadow='none'}">
                                    <div class="form-check m-0 d-flex align-items-center gap-2">
                                        <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" style="width:18px;height:18px;" {{ $isChecked ? 'checked' : '' }}>
                                        <div class="d-flex flex-column" style="margin-top:2px;">
                                            <span class="fw-bold text-dark lh-1" style="font-size:0.9rem;">
                                                {{ ucwords(str_replace('_', ' ', str_replace('manage_', '', $permission->name))) }}
                                            </span>
                                            <small class="text-muted" style="font-size:0.75rem;">Access & modify</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow" style="border-radius:12px; font-weight:700;">
                        <i class="bi bi-arrow-repeat me-2"></i> Update Role
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('selectAllBtn');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        // Check if all are currently selected
        let allSelected = Array.from(checkboxes).every(cb => cb.checked);
        if(allSelected) {
            selectAllBtn.textContent = 'Deselect All';
            selectAllBtn.classList.replace('btn-outline-primary', 'btn-primary');
        }

        function updateCardStyle(checkbox) {
            const label = checkbox.closest('label');
            if(checkbox.checked) {
                label.style.borderColor = 'var(--teal)';
                label.style.backgroundColor = 'rgba(13,115,119,0.02)';
                label.style.boxShadow = '0 2px 8px rgba(13,115,119,0.1)';
            } else {
                label.style.borderColor = 'var(--border)';
                label.style.backgroundColor = '#fff';
                label.style.boxShadow = 'none';
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() { 
                updateCardStyle(this); 
                allSelected = Array.from(checkboxes).every(c => c.checked);
                selectAllBtn.textContent = allSelected ? 'Deselect All' : 'Select All';
                selectAllBtn.classList.toggle('btn-primary', allSelected);
                selectAllBtn.classList.toggle('btn-outline-primary', !allSelected);
            });
            updateCardStyle(cb); // run on init
        });

        selectAllBtn.addEventListener('click', function() {
            allSelected = !allSelected;
            checkboxes.forEach(cb => {
                cb.checked = allSelected;
                updateCardStyle(cb);
            });
            this.textContent = allSelected ? 'Deselect All' : 'Select All';
            this.classList.toggle('btn-outline-primary', !allSelected);
            this.classList.toggle('btn-primary', allSelected);
        });
    });
</script>
@endpush
@endsection
