@props([])

{{-- ================================================================
     Desktop Sidebar
     ================================================================ --}}
<aside
    :class="sidebarOpen ? 'w-64' : 'w-16'"
    class="hidden lg:flex flex-col bg-slate-900 border-r border-slate-800 transition-all duration-300 ease-in-out flex-shrink-0 relative z-30"
>
    {{-- Logo --}}
    <div class="flex items-center h-16 px-4 border-b border-slate-800 flex-shrink-0">
        <div class="flex items-center gap-3 overflow-hidden">
            {{-- Brand icon --}}
            <div class="flex-shrink-0 w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center shadow-lg shadow-amber-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
            </div>
            {{-- Brand name (hidden when collapsed) --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity duration-200 delay-100"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="overflow-hidden"
            >
                <span class="text-white font-bold text-lg tracking-tight whitespace-nowrap">BrewFlow</span>
                <p class="text-slate-500 text-xs whitespace-nowrap">Super Admin</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto overflow-x-hidden">

        {{-- ---- Overview ---- --}}
        <div x-show="sidebarOpen" class="px-3 mb-2">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider whitespace-nowrap">Overview</p>
        </div>

        <x-super-admin.nav-item
            route="super-admin.dashboard"
            label="Dashboard"
            icon="chart-bar"
        />

        {{-- ---- Management ---- --}}
        <div x-show="sidebarOpen" class="px-3 mb-2 mt-6">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider whitespace-nowrap">Management</p>
        </div>
        <div x-show="!sidebarOpen" class="border-t border-slate-800 my-2"></div>

        <x-super-admin.nav-item
            route="super-admin.businesses.index"
            label="Businesses"
            icon="building"
        />
        <x-super-admin.nav-item
            route="super-admin.staff.index"
            label="Staff"
            icon="users"
        />

        {{-- ---- Configuration ---- --}}
        <div x-show="sidebarOpen" class="px-3 mb-2 mt-6">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider whitespace-nowrap">Configuration</p>
        </div>
        <div x-show="!sidebarOpen" class="border-t border-slate-800 my-2"></div>

        <x-super-admin.nav-item
            route="super-admin.roles.index"
            label="Roles & Permissions"
            icon="shield"
        />
        <x-super-admin.nav-item
            route="super-admin.settings.index"
            label="Platform Settings"
            icon="cog"
        />
    </nav>

    {{-- User profile strip --}}
    <div class="border-t border-slate-800 p-2 flex-shrink-0">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-800 transition-colors overflow-hidden cursor-pointer">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center">
                <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
            </div>
            <div x-show="sidebarOpen" class="overflow-hidden min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-slate-500 text-xs truncate">Super Admin</p>
            </div>
        </div>
    </div>
</aside>

{{-- ================================================================
     Mobile Sidebar (slide-over)
     ================================================================ --}}
<aside
    :class="sidebarMobileOpen ? 'translate-x-0' : '-translate-x-full'"
    class="lg:hidden fixed inset-y-0 left-0 z-30 w-64 flex flex-col bg-slate-900 border-r border-slate-800 transition-transform duration-300 ease-in-out"
>
    {{-- Mobile header --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
            </div>
            <span class="text-white font-bold text-lg">BrewFlow</span>
        </div>
        <button @click="sidebarMobileOpen = false" class="text-slate-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile nav --}}
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <div class="px-3 mb-2">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Overview</p>
        </div>
        <x-super-admin.nav-item route="super-admin.dashboard"         label="Dashboard"          icon="chart-bar" :mobile="true"/>

        <div class="px-3 mb-2 mt-6">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Management</p>
        </div>
        <x-super-admin.nav-item route="super-admin.businesses.index"  label="Businesses"         icon="building"  :mobile="true"/>
        <x-super-admin.nav-item route="super-admin.staff.index"        label="Staff"              icon="users"     :mobile="true"/>

        <div class="px-3 mb-2 mt-6">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Configuration</p>
        </div>
        <x-super-admin.nav-item route="super-admin.roles.index"        label="Roles & Permissions" icon="shield"  :mobile="true"/>
        <x-super-admin.nav-item route="super-admin.settings.index"     label="Platform Settings"   icon="cog"    :mobile="true"/>
    </nav>

    {{-- Mobile user strip --}}
    <div class="border-t border-slate-800 p-2 flex-shrink-0">
        <div class="flex items-center gap-3 p-2">
            <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-slate-500 text-xs truncate">Super Admin</p>
            </div>
        </div>
    </div>
</aside>
