<x-layouts.super-admin title="Roles & Permissions">

    <div x-data="rolesPermissions()" x-init="init()">
        <x-super-admin.breadcrumb :items="[['label' => 'Roles & Permissions', 'url' => route('super-admin.roles.index')]]" />

        {{-- Flash messages --}}
        <div x-show="errorMessage" x-transition class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-red-700" x-text="errorMessage"></p>
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
                            @click="selectRole({{ $role->id }})"
                            :class="selectedRoleId === {{ $role->id }} ? 'bg-amber-50' : ''"
                            class="w-full flex items-center justify-between p-4 hover:bg-slate-50 transition-colors text-left">
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
                                :class="selectedRoleId === {{ $role->id }} ? 'text-amber-500' : 'text-slate-300'"
                                class="w-4 h-4 transition-colors"
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
                <template x-if="selectedRole">
                    <x-ui.card>
                        <x-slot:title>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span>Permissions for</span>
                                <span class="text-amber-600" x-text="selectedRole.display_name"></span>
                                <template x-if="selectedRole.name === 'super_admin'">
                                    <x-ui.badge color="purple">All Access</x-ui.badge>
                                </template>
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
                                        @click="togglePermission('{{ $permission->name }}')"
                                        :disabled="selectedRole.name === 'super_admin' || loading"
                                        :class="{
                                                    'border-amber-200 bg-amber-50': hasPermission('{{ $permission->name }}'),
                                                    'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50': !hasPermission('{{ $permission->name }}'),
                                                    'cursor-not-allowed opacity-75': selectedRole.name === 'super_admin'
                                                }"
                                        class="flex items-center justify-between p-3 rounded-xl border transition-all text-left">
                                        <span
                                            :class="hasPermission('{{ $permission->name }}') ? 'text-amber-800 font-medium' : 'text-slate-600'"
                                            class="text-sm">
                                            {{ str_replace('.', ' → ', $permission->name) }}
                                        </span>

                                        {{-- Tick indicator --}}
                                        <div
                                            :class="hasPermission('{{ $permission->name }}') ? 'bg-amber-500' : 'border-2 border-slate-300'"
                                            class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 ml-2 transition-colors">
                                            <template x-if="hasPermission('{{ $permission->name }}')">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </template>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </x-ui.card>
                </template>

                <template x-if="!selectedRole">
                    <div class="bg-white rounded-2xl border border-slate-200 flex items-center justify-center" style="min-height: 420px;">
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
                </template>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function rolesPermissions() {
            return {
                selectedRoleId: null,
                selectedRole: null,
                roles: @json($roles),
                errorMessage: '',
                loading: false,

                init() {
                    if (this.roles.length > 0) {
                        this.selectRole(this.roles[0].id);
                    }
                },

                selectRole(roleId) {
                    this.selectedRoleId = roleId;
                    this.selectedRole = this.roles.find(r => r.id === roleId);
                    if (this.selectedRole) {
                        this.selectedRole.display_name = this.selectedRole.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    }
                },

                hasPermission(permissionName) {
                    if (!this.selectedRole) return false;
                    return this.selectedRole.permissions.some(p => p.name === permissionName);
                },

                async togglePermission(permissionName) {
                    if (!this.selectedRole || this.selectedRole.name === 'super_admin') return;

                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('{{ route("super-admin.roles.toggle-permission") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                role_id: this.selectedRole.id,
                                permission_name: permissionName
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            const permIndex = this.selectedRole.permissions.findIndex(p => p.name === permissionName);
                            if (data.hasPermission && permIndex === -1) {
                                this.selectedRole.permissions.push({
                                    name: permissionName
                                });
                            } else if (!data.hasPermission && permIndex !== -1) {
                                this.selectedRole.permissions.splice(permIndex, 1);
                            }

                            const roleIndex = this.roles.findIndex(r => r.id === this.selectedRole.id);
                            if (roleIndex !== -1) {
                                this.roles[roleIndex] = this.selectedRole;
                            }
                        } else {
                            this.errorMessage = data.error || 'Failed to update permission';
                            setTimeout(() => this.errorMessage = '', 4000);
                        }
                    } catch (error) {
                        this.errorMessage = 'An error occurred. Please try again.';
                        setTimeout(() => this.errorMessage = '', 4000);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>

    @endpush

</x-layouts.super-admin>