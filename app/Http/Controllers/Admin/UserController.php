<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles')->orWhere('role', 'super_admin')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create() 
    { 
        $roles = \Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.users.create', compact('roles')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'role'     => 'required|exists:roles,name',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);

        ActivityLog::record("Created admin user & assigned role: {$request->role}", $user);
        return redirect()->route('admin.users.index')->with('success', 'Admin user created and role assigned.');
    }

    public function edit(User $user) 
    { 
        if ($user->hasRole('Super Admin') && auth()->id() !== $user->id) {
            abort(403, 'Cannot edit another Super Admin.');
        }
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.edit', compact('user', 'roles')); 
    }

    public function update(Request $request, User $user)
    {
        if ($user->hasRole('Super Admin') && auth()->id() !== $user->id) {
            abort(403);
        }

        $request->validate([
            'name'  => 'required|string|max:100',
            'role'  => 'required|exists:roles,name',
            'email' => "required|email|unique:users,email,{$user->id}",
        ]);
        
        $user->update(['name' => $request->name, 'email' => $request->email]);
        
        if ($user->id !== auth()->id() || $user->hasRole('Super Admin') == false) {
             $user->syncRoles([$request->role]);
        }
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }
        ActivityLog::record("Updated admin user: {$user->email}", $user);
        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Cannot delete yourself.');
        if ($user->hasRole('Super Admin')) return back()->with('error', 'Cannot delete a Super Admin.');
        
        ActivityLog::record("Deleted admin user: {$user->email}", $user);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
