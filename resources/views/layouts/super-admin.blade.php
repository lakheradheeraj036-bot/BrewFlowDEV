<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'Dashboard' }} | BrewFlow Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full bg-slate-50 antialiased" x-data="{ sidebarOpen: true, sidebarMobileOpen: false }">
    {{-- Toast Notifications --}}
    <x-ui.toast-container />

    <div class="flex h-full">
        {{-- Sidebar --}}
        <x-super-admin.sidebar />

        {{-- Mobile overlay --}}
        <div
            x-show="sidebarMobileOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarMobileOpen = false"
            class="fixed inset-0 z-20 bg-black/50 lg:hidden"
            style="display:none;"></div>

        {{-- Main content area --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Top Header --}}
            <x-super-admin.header :title="$title ?? 'Dashboard'" />

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto">
                <div class="px-6 py-6">
                    {{ $slot ?? '' }}
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
    @stack('scripts')
</body>

</html>