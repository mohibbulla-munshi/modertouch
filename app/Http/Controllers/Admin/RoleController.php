<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\ActivityLog;

class RoleController extends Controller
{
    public function index()
    {
        // Don't let normal admins edit the Super Admin role to prevent lockouts
        $roles = Role::where('name', '!=', 'Super Admin')->with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        ActivityLog::record("Created role: {$role->name}");
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin') {
            abort(403, 'Super Admin role cannot be edited.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            abort(403, 'Super Admin role cannot be edited.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);
        
        $permissions = $request->permissions ?? [];
        $role->syncPermissions($permissions);

        ActivityLog::record("Updated role: {$role->name}");
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }
        
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role because it is assigned to users.');
        }

        $roleName = $role->name;
        $role->delete();
        
        ActivityLog::record("Deleted role: {$roleName}");
        return back()->with('success', 'Role deleted successfully.');
    }
}
