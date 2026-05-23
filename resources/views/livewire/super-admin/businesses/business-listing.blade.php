<div @class(['pb-24' => !empty($selectedIds)])>
    <x-super-admin.breadcrumb :items="[['label' => 'Businesses', 'url' => route('super-admin.businesses.index')]]" />

    {{-- Flash success --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3"
        x-data x-init="setTimeout(() => $el.remove(), 4000)">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Business Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage all registered businesses on the platform</p>
        </div>
        <x-ui.button href="{{ route('super-admin.businesses.create') }}" wire:navigate>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Business
        </x-ui.button>
    </div>

    {{-- Filters and table card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Filters section --}}
        <div class="px-6 py-4 bg-gradient-to-b from-slate-50/80 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    {{-- Search --}}
                    <div class="relative w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search"
                            type="text"
                            placeholder="Search businesses..."
                            class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-400 transition-all" />
                    </div>

                    {{-- Clear filters --}}
                    @if($search || $statusFilter || $typeFilter)
                    <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('typeFilter', '')"
                        class="px-3 py-2 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </button>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    {{-- Status filter --}}
                    <select wire:model.live="statusFilter"
                        class="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-400 transition-all w-32">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                        <option value="suspended">Suspended</option>
                    </select>

                    {{-- Type filter --}}
                    <select wire:model.live="typeFilter"
                        class="px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-400 transition-all w-32">
                        <option value="">All Types</option>
                        <option value="cafe">Cafe</option>
                        <option value="hotel">Hotel</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="bar">Bar</option>
                        <option value="other">Other</option>
                    </select>

                    {{-- Clear button --}}
                    <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('typeFilter', '')"
                        class="px-3 py-2 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-all flex items-center gap-1.5 border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </button>

                    {{-- Export button --}}
                    <button class="px-3 py-2 text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-all flex items-center gap-1.5 border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>



        <div class="overflow-x-auto">
            <table class="w-full ">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        {{-- Table Header Checkbox --}}
                        <th class="text-left py-4 px-6 w-12">
                            <div class="relative inline-flex items-center">

                                <input
                                    type="checkbox"
                                    wire:model.live="selectAll"
                                    class="peer appearance-none w-6 h-6 rounded-lg border-2 border-slate-300 bg-white shadow-sm cursor-pointer transition-all duration-200
                                     checked:bg-amber-500 
                                     checked:border-amber-500
                                     checked:shadow-[0_0_0_4px_color-mix(in_oklab,var(--color-amber-500)_20%,transparent)]
                                     hover:border-amber-400
                                     focus:ring-2 focus:ring-amber-200 focus:ring-offset-0">
                                <svg
                                    class="pointer-events-none absolute left-1 top-1 w-4 h-4 text-white opacity-0 scale-75 transition-all duration-200 peer-checked:opacity-100 peer-checked:scale-100"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="3.5">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                            </div>
                        </th>


                        <th class="text-left py-4 px-6 text-xs font-semibold text-slate-600 uppercase tracking-wide">Business</th>
                        <th class="text-left py-4 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wide">Type</th>
                        <th class="text-left py-4 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="text-left py-4 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wide">Plan</th>
                        <th class="text-left py-4 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wide">Location</th>
                        <th class="text-left py-4 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wide">Joined</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-slate-600 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($businesses as $business)
                    <tr class="hover:bg-slate-50/70 transition-all duration-200">

                        {{-- Checkbox --}}
                        <td class="py-4 px-6">
                            <div class="relative flex items-center justify-center">

                                <input
                                    type="checkbox"
                                    wire:model.live="selectedIds"
                                    value="{{ $business->id }}"
                                    class="peer appearance-none w-6 h-6 rounded-lg border-2 border-slate-300 bg-white shadow-sm cursor-pointer transition-all duration-200
                                    checked:bg-amber-500
                                    checked:border-amber-500
                                    checked:shadow-[0_0_0_4px_color-mix(in_oklab,var(--color-amber-500)_20%,transparent)]
                                    hover:border-amber-400
                                    focus:ring-2 focus:ring-amber-200 focus:ring-offset-0">

                                <svg
                                    class="absolute left-1 top-1 w-4 h-4 text-white pointer-events-none opacity-0 scale-75 transition-all duration-200 peer-checked:opacity-100 peer-checked:scale-100"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="3">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                            </div>
                        </td>

                        {{-- Business --}}
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">

                                {{-- Avatar --}}
                                <div class="relative flex-shrink-0">

                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm">
                                        <span class="text-white text-sm font-bold">
                                            {{ substr($business->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="min-w-0">

                                    <h4 class="text-sm font-semibold text-slate-800 truncate">
                                        {{ $business->name }}
                                    </h4>

                                    <p class="text-xs text-slate-400 mt-1 truncate">
                                        {{ $business->email ?? 'business@example.com' }}
                                    </p>

                                </div>

                            </div>
                        </td>

                        {{-- Type --}}
                        <td class="py-5 px-4">

                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200">

                                {{-- Icon --}}
                                @if($business->type == 'cafe')
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 8h1a4 4 0 010 8h-1m-1 4H6a2 2 0 01-2-2V8h14v10a2 2 0 01-2 2z" />
                                </svg>

                                @elseif($business->type == 'hotel')
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21h18M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14" />
                                </svg>

                                @elseif($business->type == 'restaurant')
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 3v7a2 2 0 002 2h1v9m4-18v18m4-18v7a2 2 0 002 2h1v9" />
                                </svg>

                                @else
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />
                                </svg>
                                @endif

                                <span class="text-sm font-medium text-slate-700 capitalize">
                                    {{ $business->type }}
                                </span>

                            </div>

                        </td>

                        {{-- Status --}}
                        <td class="py-5 px-4">

                            @if($business->status == 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Active
                            </span>

                            @elseif($business->status == 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Pending
                            </span>

                            @elseif($business->status == 'inactive')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">
                                <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                Inactive
                            </span>

                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 text-red-700 text-xs font-semibold border border-red-200">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Suspended
                            </span>
                            @endif

                        </td>

                        {{-- Plan --}}
                        <td class="py-5 px-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-violet-50 text-violet-700 text-xs font-semibold border border-violet-200 capitalize">
                                {{ $business->subscription_plan ?? 'Basic' }}
                            </span>
                        </td>

                        {{-- Location --}}
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-2 text-slate-600">

                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                </svg>

                                <span class="text-sm">
                                    {{ $business->city ?? 'New York' }}
                                </span>

                            </div>
                        </td>

                        {{-- Joined --}}
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-2 text-slate-500">

                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10" />
                                </svg>

                                <span class="text-sm">
                                    {{ $business->created_at->format('d M Y') }}
                                </span>

                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="py-5 px-6">
                            <div class="flex items-center justify-end gap-2">

                                {{-- View --}}
                                <a href="javascript:void(0)"
                                    class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-sky-600 hover:bg-sky-50 hover:border-sky-200 transition-all flex items-center justify-center"
                                    title="View">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>

                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('super-admin.businesses.edit', $business) }}"
                                    class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-all flex items-center justify-center"
                                    title="Edit">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>

                                </a>

                                {{-- Delete --}}
                                <button
                                    wire:click="confirmDelete({{ $business->id }})"
                                    class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-all flex items-center justify-center"
                                    title="Delete">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>

                                </button>

                            </div>
                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="8" class="py-24">

                            <div class="flex flex-col items-center justify-center text-center px-4">

                                <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mb-5">

                                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />

                                    </svg>

                                </div>

                                <h3 class="text-lg font-semibold text-slate-700 mb-2">
                                    No businesses found
                                </h3>

                                <p class="text-sm text-slate-400 max-w-sm leading-relaxed">
                                    Try adjusting your search or filters to find what you're looking for.
                                </p>

                            </div>

                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $businesses->links() }}
        </div>
    </div>

    {{-- Bulk Action Panel --}}
    @if(!empty($selectedIds))
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t-2 border-amber-200 shadow-2xl transition-all duration-300 ease-out"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Left side: Selection info --}}
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-800">
                        {{ count($selectedIds) }} {{ str('item')->plural(count($selectedIds)) }} selected
                    </span>
                </div>
                <button wire:click="clearSelection"
                    class="ml-2 px-3 py-1.5 text-xs text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-all">
                    Clear
                </button>
            </div>

            {{-- Right side: Actions --}}
            <div class="flex items-center gap-3">
                {{-- Status change dropdown --}}
                <div class="relative">
                    <select wire:change="bulkUpdateStatus($event.target.value)"
                        class="appearance-none px-4 py-2.5 pr-10 text-sm font-medium border border-slate-200 rounded-lg bg-white text-slate-700 hover:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-200 transition-all cursor-pointer">
                        <option value="">Change Status...</option>
                        <option value="active">Mark as Active</option>
                        <option value="pending">Mark as Pending</option>
                        <option value="inactive">Mark as Inactive</option>
                        <option value="suspended">Mark as Suspended</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>

                {{-- Delete button --}}
                <button wire:click="confirmDelete(0, true)"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Selected
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete confirmation modal --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:key="delete-modal">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            wire:click="$set('showDeleteModal', false)"></div>

        {{-- Modal panel --}}
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200 p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        {{ $isBulkDelete ? 'Delete Selected Businesses' : 'Delete Business' }}
                    </h3>
                    <p class="text-sm text-slate-500">This action cannot be undone</p>
                </div>
            </div>

            @if($isBulkDelete)
            <p class="text-sm text-slate-600 mb-6">
                Are you sure you want to delete <strong>{{ count($selectedIds) }} {{ str('business')->plural(count($selectedIds)) }}</strong>?
                All associated data will be permanently removed.
            </p>
            @else
            <p class="text-sm text-slate-600 mb-6">
                Are you sure you want to delete <strong>{{ $deletingName }}</strong>?
                All associated data will be permanently removed.
            </p>
            @endif

            <div class="flex items-center justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)"
                    class="px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancel
                </button>
                <button wire:click="deleteBusiness"
                    wire:loading.attr="disabled"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg wire:loading wire:target="deleteBusiness"
                        class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="deleteBusiness">
                        {{ $isBulkDelete ? 'Delete Selected' : 'Delete Business' }}
                    </span>
                    <span wire:loading wire:target="deleteBusiness">Deleting...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>