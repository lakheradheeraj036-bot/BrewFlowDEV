<?php

namespace App\Livewire\SuperAdmin\Roles;

use Livewire\Attributes\Reactive;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissions extends Component
{
    public ?int $selectedRoleId = null;

    public function mount(): void
    {
        // Auto-select first role
        $this->selectedRoleId = Role::first()?->id;
    }

    // -------------------------------------------------------------------------
    // Actions
    // -------------------------------------------------------------------------

    public function selectRole(int $id): void
    {
        $this->selectedRoleId = $id;
    }

    public function togglePermission(int $roleId, string $permissionName): void
    {
        $role = Role::findOrFail($roleId);

        if ($role->name === 'super_admin') {
            session()->flash('error', 'Super Admin permissions cannot be modified.');
            return;
        }

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
        } else {
            $role->givePermissionTo($permissionName);
        }
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        $roles = Role::with('permissions')->get();

        // Group permissions by the first segment before the dot (e.g. "users" in "users.create")
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0];
        });

        $selectedRole = $this->selectedRoleId
            ? Role::with('permissions')->find($this->selectedRoleId)
            : null;

        return view('livewire.super-admin.roles.roles-permissions', [
            'roles'        => $roles,
            'permissions'  => $permissions,
            'selectedRole' => $selectedRole,
        ])->layout('layouts.super-admin', ['title' => 'Roles & Permissions']);
    }
}
