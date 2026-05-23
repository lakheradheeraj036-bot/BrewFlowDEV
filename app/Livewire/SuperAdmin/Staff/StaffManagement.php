<?php

namespace App\Livewire\SuperAdmin\Staff;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class StaffManagement extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $roleFilter = '';

    #[Url]
    public string $statusFilter = '';

    public bool $showCreateModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public string $deletingName = '';

    // Create form fields
    #[Validate('required|min:2|max:100')]
    public string $newName = '';

    #[Validate('required|email|unique:users,email')]
    public string $newEmail = '';

    #[Validate('required|min:8')]
    public string $newPassword = '';

    #[Validate('required')]
    public string $newRole = 'staff';

    #[Validate('nullable|exists:businesses,id')]
    public ?int $newBusinessId = null;

    // -------------------------------------------------------------------------
    // URL filter reset helpers
    // -------------------------------------------------------------------------

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedRoleFilter(): void   { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }

    // -------------------------------------------------------------------------
    // Create staff
    // -------------------------------------------------------------------------

    public function openCreateModal(): void
    {
        $this->resetExcept(['search', 'roleFilter', 'statusFilter']);
        $this->showCreateModal = true;
    }

    public function createStaff(): void
    {
        $this->validate();

        $user = User::create([
            'name'              => $this->newName,
            'email'             => $this->newEmail,
            'password'          => Hash::make($this->newPassword),
            'business_id'       => $this->newBusinessId ?: null,
            'status'            => 'active',
            'email_verified_at' => now(),
        ]);

        $user->assignRole($this->newRole);

        $this->showCreateModal = false;
        $this->reset(['newName', 'newEmail', 'newPassword', 'newRole', 'newBusinessId']);
        session()->flash('success', 'Staff member created successfully.');
    }

    // -------------------------------------------------------------------------
    // Delete staff
    // -------------------------------------------------------------------------

    public function confirmDelete(int $id): void
    {
        $user = User::find($id);

        if ($user && ! $user->isSuperAdmin()) {
            $this->deletingId   = $id;
            $this->deletingName = $user->name;
            $this->showDeleteModal = true;
        }
    }

    public function deleteStaff(): void
    {
        if ($this->deletingId) {
            User::findOrFail($this->deletingId)->delete();
            $this->showDeleteModal = false;
            $this->deletingId      = null;
            $this->deletingName    = '';
            session()->flash('success', 'Staff member removed.');
        }
    }

    // -------------------------------------------------------------------------
    // Toggle status
    // -------------------------------------------------------------------------

    public function toggleStatus(int $id): void
    {
        $user = User::findOrFail($id);

        if (! $user->isSuperAdmin()) {
            $user->update([
                'status' => $user->status === 'active' ? 'inactive' : 'active',
            ]);
            session()->flash('success', 'Staff status updated.');
        }
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        $staff = User::query()
            ->with(['roles', 'business'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name',  'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->roleFilter, fn ($q) => $q->whereHas(
                'roles',
                fn ($q) => $q->where('name', $this->roleFilter)
            ))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $roles      = Role::orderBy('name')->get();
        $businesses = Business::where('status', 'active')->orderBy('name')->get();

        return view('livewire.super-admin.staff.staff-management', [
            'staff'      => $staff,
            'roles'      => $roles,
            'businesses' => $businesses,
        ])->layout('layouts.super-admin', ['title' => 'Staff Management']);
    }
}
