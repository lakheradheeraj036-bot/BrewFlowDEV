<div>
    <x-super-admin.breadcrumb :items="[['label' => 'Staff Management', 'url' => route('super-admin.staff.index')]]"/>

    {{-- Flash success --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3"
             x-data x-init="setTimeout(() => $el.remove(), 4000)">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Staff Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage staff members and their platform access</p>
        </div>
        <x-ui.button wire:click="openCreateModal">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Add Staff
        </x-ui.button>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search staff by name or email..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 transition-all"
                />
            </div>

            {{-- Role filter --}}
            <select
                wire:model.live="roleFilter"
                class="px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 min-w-36"
            >
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select
                wire:model.live="statusFilter"
                class="px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-700 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200 min-w-36"
            >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    {{-- Staff table --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Staff Member</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Business</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Last Login</th>
                        <th class="text-right py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($staff as $member)
                        <tr class="hover:bg-slate-50 transition-colors">
                            {{-- Name / email --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 text-white text-sm font-bold"
                                         style="background: linear-gradient(135deg, #f59e0b, #d97706)">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $member->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $member->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role badge --}}
                            <td class="py-4 px-4">
                                @foreach($member->roles as $role)
                                    @php
                                        $roleColors = [
                                            'super_admin'    => 'purple',
                                            'business_owner' => 'amber',
                                            'manager'        => 'blue',
                                            'staff'          => 'green',
                                            'kitchen_staff'  => 'orange',
                                        ];
                                        $roleColor = $roleColors[$role->name] ?? 'gray';
                                    @endphp
                                    <x-ui.badge :color="$roleColor">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </x-ui.badge>
                                @endforeach
                            </td>

                            {{-- Business --}}
                            <td class="py-4 px-4">
                                <span class="text-sm text-slate-600">{{ $member->business?->name ?? '—' }}</span>
                            </td>

                            {{-- Status badge --}}
                            <td class="py-4 px-4">
                                @php
                                    $statusColor = match($member->status) {
                                        'active'    => 'green',
                                        'suspended' => 'red',
                                        default     => 'gray',
                                    };
                                @endphp
                                <x-ui.badge :color="$statusColor">{{ ucfirst($member->status) }}</x-ui.badge>
                            </td>

                            {{-- Last login --}}
                            <td class="py-4 px-4">
                                <span class="text-sm text-slate-500">
                                    {{ $member->last_login_at?->diffForHumans() ?? 'Never' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @if(! $member->isSuperAdmin())
                                        {{-- Toggle status --}}
                                        <button
                                            wire:click="toggleStatus({{ $member->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="toggleStatus({{ $member->id }})"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                            title="{{ $member->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                        >
                                            @if($member->status === 'active')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>

                                        {{-- Delete --}}
                                        <button
                                            wire:click="confirmDelete({{ $member->id }})"
                                            class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                            title="Remove"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Protected</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">No staff found</h3>
                                    <p class="text-sm text-slate-400">
                                        {{ $search ? 'Try adjusting your search or filters' : 'Add your first staff member' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($staff->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $staff->links() }}
            </div>
        @endif
    </div>

    {{-- ===================================================================
         Create Staff Modal
         =================================================================== --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                wire:click="$set('showCreateModal', false)"
            ></div>

            {{-- Dialog --}}
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-800">Add Staff Member</h3>
                    <button
                        wire:click="$set('showCreateModal', false)"
                        class="text-slate-400 hover:text-slate-600 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form wire:submit="createStaff" class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <x-ui.input
                                wire:model="newName"
                                label="Full Name *"
                                placeholder="John Doe"
                                :error="$errors->first('newName')"
                            />
                        </div>
                        <div class="col-span-2">
                            <x-ui.input
                                wire:model="newEmail"
                                type="email"
                                label="Email Address *"
                                placeholder="staff@example.com"
                                :error="$errors->first('newEmail')"
                            />
                        </div>
                        <div class="col-span-2">
                            <x-ui.input
                                wire:model="newPassword"
                                type="password"
                                label="Password *"
                                placeholder="Min 8 characters"
                                :error="$errors->first('newPassword')"
                            />
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Role *</label>
                            <select
                                wire:model="newRole"
                                class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200"
                            >
                                @foreach($roles->where('name', '!=', 'super_admin') as $role)
                                    <option value="{{ $role->name }}">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('newRole')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Business --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Assign to Business</label>
                            <select
                                wire:model="newBusinessId"
                                class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:ring-2 focus:border-amber-400 focus:ring-amber-200"
                            >
                                <option value="">No business</option>
                                @foreach($businesses as $business)
                                    <option value="{{ $business->id }}">{{ $business->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button
                            type="button"
                            wire:click="$set('showCreateModal', false)"
                            class="px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="createStaff"
                            class="px-4 py-2.5 text-sm font-medium text-white bg-amber-500 hover:bg-amber-400 rounded-xl transition-colors flex items-center gap-2 disabled:opacity-60"
                        >
                            <svg wire:loading wire:target="createStaff" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Create Staff
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ===================================================================
         Delete Confirmation Modal
         =================================================================== --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                wire:click="$set('showDeleteModal', false)"
            ></div>

            {{-- Dialog --}}
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Remove Staff Member</h3>
                        <p class="text-sm text-slate-500">This action cannot be undone</p>
                    </div>
                </div>

                <p class="text-sm text-slate-600 mb-6">
                    Are you sure you want to remove <strong>{{ $deletingName }}</strong>?
                    Their account and all associated data will be permanently deleted.
                </p>

                <div class="flex items-center justify-end gap-3">
                    <button
                        wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="deleteStaff"
                        wire:loading.attr="disabled"
                        wire:target="deleteStaff"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors flex items-center gap-2"
                    >
                        <svg wire:loading wire:target="deleteStaff" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Remove
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
