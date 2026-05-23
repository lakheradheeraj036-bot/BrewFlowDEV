@props(['title' => 'Dashboard'])

<header class="flex-shrink-0 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-10">
    <div class="flex items-center gap-4">
        {{-- Mobile hamburger --}}
        <button
            @click="sidebarMobileOpen = !sidebarMobileOpen"
            class="lg:hidden text-slate-500 hover:text-slate-700 transition-colors"
            aria-label="Open navigation"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Desktop collapse toggle --}}
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all"
            aria-label="Toggle sidebar"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <h1 class="text-lg font-semibold text-slate-800">{{ $title }}</h1>
    </div>

    <div class="flex items-center gap-3">
        {{-- Search --}}
        <div class="hidden md:flex items-center gap-2 bg-slate-100 rounded-xl px-3 py-2 w-64">
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                placeholder="Search..."
                class="bg-transparent text-sm text-slate-600 placeholder-slate-400 focus:outline-none w-full"
            />
        </div>

        {{-- Notifications --}}
        <button
            class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors"
            aria-label="Notifications"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            {{-- Unread badge --}}
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        </button>

        {{-- User dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button
                @click="open = !open"
                @click.away="open = false"
                class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100 transition-colors"
            >
                <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-slate-700 leading-none">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Super Admin</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown panel --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50"
                style="display:none;"
            >
                <div class="px-4 py-2 border-b border-slate-100">
                    <p class="text-xs text-slate-500">Signed in as</p>
                    <p class="text-sm font-medium text-slate-700 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>

                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>

                <div class="border-t border-slate-100 mt-1"></div>

                <form method="POST" action="{{ route('super-admin.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
