<div wire:id="roles-permissions-component">
    <x-super-admin.breadcrumb :items="[['label' => 'Roles & Permissions', 'url' => route('super-admin.roles.index')]]"/>

    {{-- Flash error --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3"
             x-data x-init="setTimeout(() => $el.remove(), 4000)">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Page header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Roles & Permissions</h2>
        <p class="text-sm text-slate-500 mt-0.5">Manage role-based access control for the platform</p>
        {{-- Debug info --}}
        <p class="text-xs text-blue-600 mt-2">Selected Role ID: {{ $selectedRoleId ?? 'None' }}</p>
    </div>

    <div style="display:flex; gap:24px; align-items:flex-start;">

        {{-- ================================================================
             Roles list panel (left column)
             ================================================================ --}}
        <div style="width:240px; flex-shrink:0; position:relative; z-index:10;">
            <x-ui.card title="Roles" :padding="false">
                <div class="divide-y divide-slate-100">
                    @foreach($roles as $role)
                        @php
                            $roleColors = [
                                'super_admin'    => 'purple',
                                'business_owner' => 'amber',
                                'manager'        => 'blue',
                                'staff'          => 'green',
                                'kitchen_staff'  => 'orange',
                            ];
                            $color = $roleColors[$role->name] ?? 'gray';
                            $dotClass = match($color) {
                                'purple' => 'bg-purple-500',
                                'amber'  => 'bg-amber-500',
                                'blue'   => 'bg-blue-500',
                                'green'  => 'bg-emerald-500',
                                'orange' => 'bg-orange-500',
                                default  => 'bg-slate-400',
                            };
                        @endphp
                        <button
                            type="button"
                            @click="$wire.selectRole({{ $role->id }})"
                            wire:key="role-{{ $role->id }}"
                            style="cursor:pointer; position:relative; z-index:1;"
                            @class([
                                'w-full flex items-center justify-between p-4 hover:bg-slate-50 transition-colors text-left',
                                'bg-amber-50' => $selectedRoleId === $role->id,
                            ])
                        >
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
                                @class([
                                    'w-4 h-4 transition-colors',
                                    'text-amber-500' => $selectedRoleId === $role->id,
                                    'text-slate-300' => $selectedRoleId !== $role->id,
                                ])
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        {{-- ================================================================
             Permissions matrix panel (right columns)
             ================================================================ --}}
        <div style="flex:1; min-width:0;" wire:key="permissions-panel-{{ $selectedRoleId ?? 'none' }}">
            @if($selectedRole)
                <x-ui.card>
                    <x-slot:title>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span>Permissions for</span>
                            <span class="text-amber-600">{{ ucwords(str_replace('_', ' ', $selectedRole->name)) }}</span>
                            @if($selectedRole->name === 'super_admin')
                                <x-ui.badge color="purple">All Access</x-ui.badge>
                            @endif
                        </div>
                    </x-slot:title>

                    @if($permissions->isEmpty())
                        {{-- No permissions seeded yet --}}
                        <div class="py-12 text-center">
                            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
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
                                            @php $hasPermission = $selectedRole->hasPermissionTo($permission->name); @endphp
                                            <button
                                                wire:click="togglePermission({{ $selectedRole->id }}, '{{ $permission->name }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="togglePermission({{ $selectedRole->id }}, '{{ $permission->name }}')"
                                                @class([
                                                    'flex items-center justify-between p-3 rounded-xl border transition-all text-left',
                                                    'border-amber-200 bg-amber-50'                       => $hasPermission,
                                                    'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50' => ! $hasPermission,
                                                    'cursor-not-allowed opacity-75'                      => $selectedRole->name === 'super_admin',
                                                ])
                                                @disabled($selectedRole->name === 'super_admin')
                                            >
                                                <span @class([
                                                    'text-sm',
                                                    'text-amber-800 font-medium' => $hasPermission,
                                                    'text-slate-600'             => ! $hasPermission,
                                                ])>
                                                    {{ str_replace('.', ' → ', $permission->name) }}
                                                </span>

                                                {{-- Tick indicator --}}
                                                <div @class([
                                                    'w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 ml-2 transition-colors',
                                                    'bg-amber-500'          => $hasPermission,
                                                    'border-2 border-slate-300' => ! $hasPermission,
                                                ])>
                                                    @if($hasPermission)
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-ui.card>

            @else
                {{-- Empty state — no role selected --}}
                <div class="bg-white rounded-2xl border border-slate-200 flex items-center justify-center" style="min-height: 420px;">
                    <div class="text-center px-6">
                        <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-700 mb-1">Select a role</h3>
                        <p class="text-sm text-slate-400">
                            Choose a role from the left panel to view and manage its permissions
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
