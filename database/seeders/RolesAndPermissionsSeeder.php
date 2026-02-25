<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define all available modules
        $modules = [
            'dashboard' => 'Manage Dashboard',
            'categories' => 'Manage Categories',
            'products' => 'Manage Products',
            'tags' => 'Manage Tags',
            'orders' => 'Manage Orders',
            'customers' => 'Manage Customers',
            'coupons' => 'Manage Coupons',
            'sliders' => 'Manage Sliders',
            'newsletters' => 'Manage Newsletters',
            'inquiries' => 'Manage Inquiries',
            'reviews' => 'Manage Reviews',
            'reports' => 'View Sales Reports',
            'shipping' => 'Manage Shipping',
            'admin_users' => 'Manage Admin Users',
            'activity_log' => 'View Activity Logs',
            'settings' => 'Manage Settings',
        ];

        // 2. Create permissions
        foreach ($modules as $slug => $label) {
            Permission::firstOrCreate(['name' => "manage_$slug", 'guard_name' => 'web']);
        }

        // 3. Create Super Admin role and assign all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        // 4. Create standard Admin role (example: maybe lacks settings/users)
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([
            'manage_dashboard', 'manage_categories', 'manage_products', 'manage_tags',
            'manage_orders', 'manage_customers', 'manage_coupons', 'manage_sliders',
            'manage_newsletters', 'manage_inquiries', 'manage_reviews', 'manage_reports'
        ]);

        // 5. Assign Super Admin role to existing users whose role column is 'super_admin'
        $superAdmins = User::where('role', 'super_admin')->get();
        foreach ($superAdmins as $user) {
            $user->assignRole('Super Admin');
        }

        // Assign Admin role to existing 'admin' users
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $user) {
            $user->assignRole('Admin');
        }
    }
}
