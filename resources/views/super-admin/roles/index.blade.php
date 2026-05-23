<x-layouts.super-admin title="Roles & Permissions">

    <div id="roles-permissions-app">
        <x-super-admin.breadcrumb :items="[['label' => 'Roles & Permissions', 'url' => route('super-admin.roles.index')]]" />

        {{-- Flash messages --}}
        <div id="error-message" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 transition-opacity duration-300">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-red-700" id="error-text"></p>
        </div>

        {{-- Page header --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800">Roles & Permissions</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage role-based access control for the platform</p>
        </div>

        <div style="display:flex; gap:24px; align-items:flex-start;">

            {{-- Roles list panel (left column) --}}
            <div style="width:240px; flex-shrink:0;">
                <x-ui.card title="Roles" :padding="false">
                    <div class="divide-y divide-slate-100">
                        @foreach($roles as $role)
                        @php
                        $roleColors = [
                        'super_admin' => 'purple',
                        'business_owner' => 'amber',
                        'manager' => 'blue',
                        'staff' => 'green',
                        'kitchen_staff' => 'orange',
                        ];
                        $color = $roleColors[$role->name] ?? 'gray';
                        $dotClass = match($color) {
                        'purple' => 'bg-purple-500',
                        'amber' => 'bg-amber-500',
                        'blue' => 'bg-blue-500',
                        'green' => 'bg-emerald-500',
                        'orange' => 'bg-orange-500',
                        default => 'bg-slate-400',
                        };
                        @endphp
                        <button
                            type="button"
                            data-role-id="{{ $role->id }}"
                            class="role-button w-full flex items-center justify-between p-4 hover:bg-slate-50 transition-colors text-left cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full {{ $dotClass }}"></div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $role->permissions->count() }} permissions</p>
                                </div>
                            </div>
                            <svg
                                class="role-arrow w-4 h-4 transition-colors text-slate-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        @endforeach
                    </div>
                </x-ui.card>
            </div>

            {{-- Permissions matrix panel (right column) --}}
            <div style="flex:1; min-width:0;">
                <div id="permissions-panel" class="hidden">
                    <x-ui.card>
                        <x-slot:title>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span>Permissions for</span>
                                <span class="text-amber-600" id="selected-role-name"></span>
                                <span id="super-admin-badge" class="hidden">
                                    <x-ui.badge color="purple">All Access</x-ui.badge>
                                </span>
                            </div>
                        </x-slot:title>

                        @if($permissions->isEmpty())
                        <div class="py-12 text-center">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700 mb-1">No permissions defined</p>
                            <p class="text-xs text-slate-400">Run your permission seeder to populate this list.</p>
                        </div>
                        @else
                        <div class="space-y-6">
                            @foreach($permissions as $group => $groupPermissions)
                            <div>
                                {{-- Group heading --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest capitalize">
                                        {{ $group }}
                                    </h4>
                                    <div class="flex-1 h-px bg-slate-100"></div>
                                    <span class="text-xs text-slate-400">{{ $groupPermissions->count() }}</span>
                                </div>

                                {{-- Permission toggles --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($groupPermissions as $permission)
                                    <button
                                        type="button"
                                        data-permission="{{ $permission->name }}"
                                        class="permission-button flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50 transition-all text-left cursor-pointer">
                                        <span class="permission-text text-sm text-slate-600">
                                            {{ str_replace('.', ' → ', $permission->name) }}
                                        </span>

                                        {{-- Tick indicator --}}
                                        <div class="permission-indicator w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 ml-2 transition-colors border-2 border-slate-300">
                                            <svg class="permission-tick w-3 h-3 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </x-ui.card>
                </div>

                <div id="empty-state" class="bg-white rounded-2xl border border-slate-200 flex items-center justify-center" style="min-height: 420px;">
                    <div class="text-center px-6">
                        <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-700 mb-1">Select a role</h3>
                        <p class="text-sm text-slate-400">
                            Choose a role from the left panel to view and manage its permissions
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @push('scripts') --}}    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Roles permissions script loaded');
            
            const roles = @json($roles);
            let selectedRoleId = null;
            let selectedRole = null;
            let loading = false;

            const errorMessageEl = document.getElementById('error-message');
            const errorTextEl = document.getElementById('error-text');
            const permissionsPanel = document.getElementById('permissions-panel');
            const emptyState = document.getElementById('empty-state');
            const selectedRoleNameEl = document.getElementById('selected-role-name');
            const superAdminBadge = document.getElementById('super-admin-badge');
            const appContainer = document.getElementById('roles-permissions-app');

            console.log('Roles data:', roles);
            console.log('App container:', appContainer);

            function showErrorMessage(message) {
                errorTextEl.textContent = message;
                errorMessageEl.classList.remove('hidden');
                setTimeout(() => {
                    errorMessageEl.classList.add('hidden');
                }, 4000);
            }

            function selectRole(roleId) {
                console.log('selectRole called with roleId:', roleId);
                selectedRoleId = roleId;
                selectedRole = roles.find(r => r.id === roleId);
                
                if (selectedRole) {
                    selectedRole.display_name = selectedRole.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    console.log('Selected role:', selectedRole);
                }

                updateUI();
            }

            function hasPermission(permissionName) {
                if (!selectedRole) return false;
                return selectedRole.permissions.some(p => p.name === permissionName);
            }

            function updateUI() {
                console.log('updateUI called, selectedRole:', selectedRole, 'loading:', loading);
                
                // Update role buttons
                document.querySelectorAll('.role-button').forEach(btn => {
                    const roleId = parseInt(btn.dataset.roleId);
                    const arrow = btn.querySelector('.role-arrow');
                    
                    if (selectedRoleId === roleId) {
                        btn.classList.add('bg-amber-50');
                        arrow.classList.remove('text-slate-300');
                        arrow.classList.add('text-amber-500');
                    } else {
                        btn.classList.remove('bg-amber-50');
                        arrow.classList.remove('text-amber-500');
                        arrow.classList.add('text-slate-300');
                    }
                });

                // Update panels
                if (selectedRole) {
                    emptyState.classList.add('hidden');
                    permissionsPanel.classList.remove('hidden');
                    selectedRoleNameEl.textContent = selectedRole.display_name;
                    
                    if (selectedRole.name === 'super_admin') {
                        superAdminBadge.classList.remove('hidden');
                    } else {
                        superAdminBadge.classList.add('hidden');
                    }

                    // Update permission buttons
                    const permissionButtons = document.querySelectorAll('.permission-button');
                    console.log('Found permission buttons:', permissionButtons.length);
                    
                    permissionButtons.forEach(btn => {
                        const permissionName = btn.dataset.permission;
                        const text = btn.querySelector('.permission-text');
                        const indicator = btn.querySelector('.permission-indicator');
                        const tick = btn.querySelector('.permission-tick');

                        if (hasPermission(permissionName)) {
                            btn.classList.remove('border-slate-200', 'bg-white', 'hover:border-slate-300', 'hover:bg-slate-50');
                            btn.classList.add('border-amber-200', 'bg-amber-50');
                            text.classList.remove('text-slate-600');
                            text.classList.add('text-amber-800', 'font-medium');
                            indicator.classList.remove('border-2', 'border-slate-300');
                            indicator.classList.add('bg-amber-500');
                            tick.classList.remove('hidden');
                        } else {
                            btn.classList.add('border-slate-200', 'bg-white', 'hover:border-slate-300', 'hover:bg-slate-50');
                            btn.classList.remove('border-amber-200', 'bg-amber-50');
                            text.classList.add('text-slate-600');
                            text.classList.remove('text-amber-800', 'font-medium');
                            indicator.classList.add('border-2', 'border-slate-300');
                            indicator.classList.remove('bg-amber-500');
                            tick.classList.add('hidden');
                        }

                        // Disable for super_admin
                        if (selectedRole.name === 'super_admin' || loading) {
                            btn.disabled = true;
                            btn.classList.add('cursor-not-allowed', 'opacity-75');
                            btn.classList.remove('cursor-pointer');
                        } else {
                            btn.disabled = false;
                            btn.classList.remove('cursor-not-allowed', 'opacity-75');
                            btn.classList.add('cursor-pointer');
                        }
                    });
                } else {
                    emptyState.classList.remove('hidden');
                    permissionsPanel.classList.add('hidden');
                }
            }

            async function togglePermission(permissionName) {
                if (!selectedRole || selectedRole.name === 'super_admin') return;

                loading = true;
                updateUI();

                try {
                    const response = await fetch('{{ route("super-admin.roles.toggle-permission") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            role_id: selectedRole.id,
                            permission_name: permissionName
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const permIndex = selectedRole.permissions.findIndex(p => p.name === permissionName);
                        if (data.hasPermission && permIndex === -1) {
                            selectedRole.permissions.push({ name: permissionName });
                        } else if (!data.hasPermission && permIndex !== -1) {
                            selectedRole.permissions.splice(permIndex, 1);
                        }

                        const roleIndex = roles.findIndex(r => r.id === selectedRole.id);
                        if (roleIndex !== -1) {
                            roles[roleIndex] = selectedRole;
                        }

                        updateUI();
                    } else {
                        showErrorMessage(data.error || 'Failed to update permission');
                    }
                } catch (error) {
                    showErrorMessage('An error occurred. Please try again.');
                } finally {
                    loading = false;
                    updateUI();
                }
            }

            // Event listeners using event delegation
            appContainer.addEventListener('click', function(e) {
                console.log('Click event on app container:', e.target);
                
                // Handle role button clicks
                const roleButton = e.target.closest('.role-button');
                if (roleButton) {
                    console.log('Role button clicked:', roleButton.dataset.roleId);
                    const roleId = parseInt(roleButton.dataset.roleId);
                    selectRole(roleId);
                }

                // Handle permission button clicks
                const permissionButton = e.target.closest('.permission-button');
                if (permissionButton) {
                    console.log('Permission button clicked:', permissionButton.dataset.permission);
                    const permissionName = permissionButton.dataset.permission;
                    togglePermission(permissionName);
                }
            });

            // Initialize
            if (roles.length > 0) {
                selectRole(roles[0].id);
            }
        });
    </script>
    {{-- @endpush --}}

</x-layouts.super-admin>