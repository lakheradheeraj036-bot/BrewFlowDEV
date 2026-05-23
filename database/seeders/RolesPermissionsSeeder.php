<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear stale permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -----------------------------------------------------------------------
        // Permissions
        // -----------------------------------------------------------------------
        $permissions = [
            // Business
            'business.view', 'business.create', 'business.edit', 'business.delete',
            'business.suspend', 'business.approve',
            // Staff
            'staff.view', 'staff.create', 'staff.edit', 'staff.delete',
            'staff.assign-role',
            // Roles & Permissions
            'role.view', 'role.create', 'role.edit', 'role.delete',
            'permission.view', 'permission.assign',
            // Settings
            'settings.view', 'settings.edit',
            // Dashboard
            'dashboard.view',
            // Orders
            'order.view', 'order.create', 'order.edit', 'order.delete',
            // Menu
            'menu.view', 'menu.create', 'menu.edit', 'menu.delete',
            // Reports
            'report.view', 'report.export',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // -----------------------------------------------------------------------
        // Roles
        // -----------------------------------------------------------------------

        // Super Admin — every permission
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Business Owner
        $businessOwner = Role::firstOrCreate(['name' => 'business_owner', 'guard_name' => 'web']);
        $businessOwner->givePermissionTo([
            'dashboard.view',
            'staff.view', 'staff.create', 'staff.edit', 'staff.delete', 'staff.assign-role',
            'menu.view',  'menu.create',  'menu.edit',  'menu.delete',
            'order.view', 'order.create', 'order.edit',
            'report.view', 'report.export',
        ]);

        // Manager
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->givePermissionTo([
            'dashboard.view',
            'staff.view', 'staff.create', 'staff.edit',
            'menu.view',  'menu.create',  'menu.edit',
            'order.view', 'order.create', 'order.edit',
            'report.view',
        ]);

        // Staff
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->givePermissionTo([
            'dashboard.view',
            'order.view', 'order.create', 'order.edit',
            'menu.view',
        ]);

        // Kitchen Staff
        $kitchenStaff = Role::firstOrCreate(['name' => 'kitchen_staff', 'guard_name' => 'web']);
        $kitchenStaff->givePermissionTo([
            'dashboard.view',
            'order.view', 'order.edit',
            'menu.view',
        ]);
    }
}
