<x-layouts.super-admin title="Businesses">
    <div id="businessesApp" class="pb-32">
        {{-- Breadcrumb --}}
        <x-super-admin.breadcrumb :items="[['label' => 'Businesses', 'url' => route('super-admin.businesses.index')]]" />

        {{-- Page header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Business Management</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage all registered businesses on the platform</p>
            </div>
            <x-ui.button href="{{ route('super-admin.businesses.create') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Business
            </x-ui.button>
        </div>

        {{-- Main card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Filters section --}}
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-b from-slate-50/80 to-white">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            id="businessSearch"
                            type="text"
                            value="{{ request('q') }}"
                            placeholder="Search businesses by name, email, or city..."
                            class="w-full pl-9 pr-9 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-400 transition-all" />
                        <div id="searchSpinner" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none hidden">
                            <svg class="w-4 h-4 text-amber-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <select id="statusFilter" name="status" class="min-w-[12rem] px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-400 transition-all">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>

                        <select id="typeFilter" name="type" class="min-w-[12rem] px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-400 transition-all">
                            <option value="">All Types</option>
                            <option value="cafe" {{ request('type') === 'cafe' ? 'selected' : '' }}>Cafe</option>
                            <option value="hotel" {{ request('type') === 'hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="restaurant" {{ request('type') === 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                            <option value="bar" {{ request('type') === 'bar' ? 'selected' : '' }}>Bar</option>
                            <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>

                        @if(request('q') || request('status') || request('type'))
                        <button id="clearFiltersButton" type="button" class="inline-flex items-center justify-center w-11 h-11 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span class="sr-only">Clear filters</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div id="businessTableWrapper" class="relative overflow-x-auto">
                <div id="tableOverlay" class="absolute inset-0 z-20 hidden items-center justify-center bg-white/70 backdrop-blur-sm">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-amber-500 animate-spin mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm font-medium text-slate-600">Updating results…</p>
                    </div>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left py-4 px-6 w-12">
                                <div class="flex items-center justify-center">
                                    <label class="relative flex items-center justify-center cursor-pointer">
                                        <input id="selectAllCheckbox" type="checkbox" class="peer sr-only" />
                                        <div class="relative w-5 h-5 rounded-lg border-2 border-slate-300 bg-white shadow-sm transition-all duration-200 peer-checked:bg-amber-500 peer-checked:border-amber-500 hover:border-amber-400 peer-hover:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:ring-offset-0">
                                            <svg class="w-3 h-3 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </label>
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
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center">
                                    <label class="relative flex items-center justify-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            data-business-id="{{ $business->id }}"
                                            class="business-checkbox peer sr-only" />
                                        <div class="relative w-5 h-5 rounded-lg border-2 border-slate-300 bg-white shadow-sm transition-all duration-200 peer-checked:bg-amber-500 peer-checked:border-amber-500 hover:border-amber-400 peer-hover:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200 focus:ring-offset-0">
                                            <svg class="w-3 h-3 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm flex-shrink-0">
                                        <span class="text-white text-sm font-bold">{{ substr($business->name, 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-semibold text-slate-800 truncate">{{ $business->name }}</h4>
                                        <p class="text-xs text-slate-400 mt-1 truncate">{{ $business->email ?? 'business@example.com' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-4">
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200">
                                    @if($business->type == 'cafe')
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1m-1 4H6a2 2 0 01-2-2V8h14v10a2 2 0 01-2 2z" />
                                    </svg>
                                    @elseif($business->type == 'hotel')
                                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7a2 2 0 012-2h10a2 2 0 012 2v14" />
                                    </svg>
                                    @elseif($business->type == 'restaurant')
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 3v7a2 2 0 002 2h1v9m4-18v18m4-18v7a2 2 0 002 2h1v9" />
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />
                                    </svg>
                                    @endif
                                    <span class="text-sm font-medium text-slate-700 capitalize">{{ $business->type }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-4">
                                @if($business->status == 'active')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>Active
                                </span>
                                @elseif($business->status == 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-200">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>Pending
                                </span>
                                @elseif($business->status == 'inactive')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">
                                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>Inactive
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 text-red-700 text-xs font-semibold border border-red-200">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>Suspended
                                </span>
                                @endif
                            </td>
                            <td class="py-5 px-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-violet-50 text-violet-700 text-xs font-semibold border border-violet-200 capitalize">
                                    {{ $business->subscription_plan ?? 'Basic' }}
                                </span>
                            </td>
                            <td class="py-5 px-4">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span class="text-sm">{{ $business->city ?? 'New York' }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-4">
                                <div class="flex items-center gap-2 text-slate-500">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10" />
                                    </svg>
                                    <span class="text-sm">{{ $business->created_at->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="w-9 h-9 rounded-lg border border-slate-200 text-slate-400 hover:text-sky-600 hover:bg-sky-50 hover:border-sky-200 transition-all flex items-center justify-center" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <!-- <button type="button" class="w-9 h-9 rounded-lg border border-slate-200 text-slate-400 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-all flex items-center justify-center" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button> -->

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
                                
                                    <button type="button" data-delete-id="{{ $business->id }}" data-delete-name="{{ addslashes($business->name) }}" class="delete-business-button w-9 h-9 rounded-lg border border-slate-200 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-all flex items-center justify-center" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-700 mb-1">No businesses found</h3>
                                    <p class="text-sm text-slate-400 max-w-sm">Try adjusting your search or filters to find what you're looking for.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="text-sm text-slate-600">
                        Showing <span class="font-semibold">{{ $businesses->firstItem() ?? 0 }}</span>–<span class="font-semibold">{{ $businesses->lastItem() ?? 0 }}</span> of <span class="font-semibold">{{ $businesses->total() }}</span> businesses
                    </div>
                    <div id="paginationContainer" class="flex justify-end">
                        {{ $businesses->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating bulk action toolbar --}}
        <div id="bulkActionBar" class="hidden fixed bottom-6 left-1/2 z-40 w-[min(96%,45rem)] -translate-x-1/2 transform rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl transition-transform duration-300">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="flex items-center gap-2.5 rounded-xl bg-amber-50 px-3.5 py-2 border border-amber-200">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800"><span id="selectedCountText">0</span> selected</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 flex-shrink-0">
                    <div class="relative">
                        <select id="bulkStatusSelect" class="appearance-none px-3 py-2 pr-9 text-sm font-medium border border-slate-200 rounded-lg bg-white text-slate-700 hover:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-200 transition-all min-w-[160px]" disabled>
                            <option value="">Change Status...</option>
                            <option value="active">Mark as Active</option>
                            <option value="pending">Mark as Pending</option>
                            <option value="inactive">Mark as Inactive</option>
                            <option value="suspended">Mark as Suspended</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        <div id="bulkStatusSpinner" class="absolute right-3 top-1/2 hidden -translate-y-1/2 text-amber-500">
                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <button id="bulkDeleteButton" type="button" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-3.5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-600 disabled:cursor-not-allowed disabled:bg-red-400 whitespace-nowrap" disabled>
                        <svg id="bulkDeleteSpinner" class="hidden h-3.5 w-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Delete
                    </button>
                    <button id="clearSelectionButton" type="button" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50 flex-shrink-0">
                        <span class="sr-only">Clear selected items</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>



        <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200 p-6 shadow-xl">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Delete Business?</h3>
                        <p class="text-sm text-slate-500">This action cannot be undone.</p>
                    </div>
                </div>
                <p id="deleteModalMessage" class="text-sm text-slate-600 mb-6">Are you sure you want to delete this business? This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <button id="cancelDeleteButton" type="button" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 transition">Cancel</button>
                    <button id="confirmDeleteButton" type="button" class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition">
                        <svg id="deleteSpinner" class="hidden h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="confirmDeleteText">Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const state = {
                selectedIds: new Set(),
                isSearchLoading: false,
                isStatusLoading: false,
                isDeleteLoading: false,
                activeDeleteId: null,
                activeDeleteName: '',
            };

            const pageUrl = "{{ route('super-admin.businesses.index') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            const app = document.getElementById('businessesApp');
            const searchInput = document.getElementById('businessSearch');
            const statusFilter = document.getElementById('statusFilter');
            const typeFilter = document.getElementById('typeFilter');
            const clearFiltersButton = document.getElementById('clearFiltersButton');
            const tableWrapper = document.getElementById('businessTableWrapper');
            const tableOverlay = document.getElementById('tableOverlay');
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const bulkActionBar = document.getElementById('bulkActionBar');
            const selectedCountText = document.getElementById('selectedCountText');
            const bulkStatusSelect = document.getElementById('bulkStatusSelect');
            const bulkStatusSpinner = document.getElementById('bulkStatusSpinner');
            const bulkDeleteButton = document.getElementById('bulkDeleteButton');
            const clearSelectionButton = document.getElementById('clearSelectionButton');
            const deleteModal = document.getElementById('deleteModal');
            const deleteModalMessage = document.getElementById('deleteModalMessage');
            const cancelDeleteButton = document.getElementById('cancelDeleteButton');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            const confirmDeleteText = document.getElementById('confirmDeleteText');
            const deleteSpinner = document.getElementById('deleteSpinner');

            const debounce = (fn, delay) => {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            };

            const toggleOverlay = (show) => {
                tableOverlay.classList.toggle('hidden', !show);
            };

            const resetSelection = () => {
                state.selectedIds.clear();
                const checkboxes = document.querySelectorAll('.business-checkbox');
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
                selectAllCheckbox.checked = false;
                selectedCountText.textContent = '0';
                bulkActionBar.classList.add('hidden');
                bulkStatusSelect.disabled = true;
                bulkDeleteButton.disabled = true;
                bulkStatusSelect.value = '';
            };

            const updateSelectionUI = () => {
                const count = state.selectedIds.size;
                selectedCountText.textContent = String(count);
                bulkActionBar.classList.toggle('hidden', count === 0);
                bulkStatusSelect.disabled = count === 0;
                bulkDeleteButton.disabled = count === 0;
                selectAllCheckbox.checked = count > 0 && Array.from(document.querySelectorAll('.business-checkbox')).every((checkbox) => checkbox.checked);
            };

            const bindRowCheckboxes = () => {
                document.querySelectorAll('.business-checkbox').forEach((checkbox) => {
                    checkbox.removeEventListener('change', handleRowCheckboxChange);
                    checkbox.addEventListener('change', handleRowCheckboxChange);
                });
            };

            const handleRowCheckboxChange = function() {
                const id = this.dataset.businessId;
                if (this.checked) {
                    state.selectedIds.add(id);
                } else {
                    state.selectedIds.delete(id);
                    selectAllCheckbox.checked = false;
                }
                updateSelectionUI();
            };

            const bindSelectAllCheckbox = () => {
                selectAllCheckbox.removeEventListener('change', handleSelectAllChange);
                selectAllCheckbox.addEventListener('change', handleSelectAllChange);
            };

            const handleSelectAllChange = function() {
                const checked = this.checked;
                document.querySelectorAll('.business-checkbox').forEach((checkbox) => {
                    checkbox.checked = checked;
                    const id = checkbox.dataset.businessId;
                    if (checked) {
                        state.selectedIds.add(id);
                    } else {
                        state.selectedIds.delete(id);
                    }
                });
                updateSelectionUI();
            };

            const bindDeleteButtons = () => {
                document.querySelectorAll('.delete-business-button').forEach((button) => {
                    button.removeEventListener('click', handleDeleteButtonClick);
                    button.addEventListener('click', handleDeleteButtonClick);
                });
            };

            const handleDeleteButtonClick = function() {
                state.activeDeleteId = this.dataset.deleteId;
                state.activeDeleteName = this.dataset.deleteName || 'this business';
                state.isDeleteLoading = false;
                deleteSpinner.classList.add('hidden');
                confirmDeleteText.textContent = 'Delete';
                deleteModalMessage.textContent = `Are you sure you want to delete ${state.activeDeleteName}? This action cannot be undone.`;
                deleteModal.classList.remove('hidden');
            };

            const bindPaginationLinks = () => {
                const links = tableWrapper.querySelectorAll('#paginationContainer a');
                links.forEach((link) => {
                    link.removeEventListener('click', handlePaginationLinkClick);
                    link.addEventListener('click', handlePaginationLinkClick);
                });
            };

            const handlePaginationLinkClick = function(event) {
                event.preventDefault();
                loadPageFromUrl(this.href);
            };

            const refreshTableContent = (html) => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newWrapper = doc.getElementById('businessTableWrapper');
                if (!newWrapper) {
                    console.error('Unable to refresh table content.');
                    return;
                }
                tableWrapper.innerHTML = newWrapper.innerHTML;
                resetSelection();
                bindRowCheckboxes();
                bindSelectAllCheckbox();
                bindDeleteButtons();
                bindPaginationLinks();
                updateSelectionUI();
            };

            const getQueryParameters = () => {
                const params = new URLSearchParams();
                const searchValue = searchInput.value.trim();
                if (searchValue) params.set('q', searchValue);
                if (statusFilter.value) params.set('status', statusFilter.value);
                if (typeFilter.value) params.set('type', typeFilter.value);
                return params;
            };

            const loadPageFromUrl = (url) => {
                const parsedUrl = new URL(url, window.location.origin);
                const params = parsedUrl.searchParams;
                searchInput.value = params.get('q') || '';
                statusFilter.value = params.get('status') || '';
                typeFilter.value = params.get('type') || '';
                fetchAndUpdateTable(params);
            };

            const fetchAndUpdateTable = (params) => {
                state.isSearchLoading = true;
                toggleOverlay(true);
                searchSpinner.classList.remove('hidden');
                const url = `${pageUrl}?${params.toString()}`;
                return fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        },
                    })
                    .then((response) => response.text())
                    .then((html) => {
                        history.replaceState({}, '', url);
                        refreshTableContent(html);
                    })
                    .catch((error) => {
                        console.error(error);
                    })
                    .finally(() => {
                        state.isSearchLoading = false;
                        toggleOverlay(false);
                        searchSpinner.classList.add('hidden');
                    });
            };

            const showBulkStatusProcessing = (stateOn) => {
                bulkStatusSelect.disabled = stateOn;
                bulkStatusSpinner.classList.toggle('hidden', !stateOn);
            };

            const showDeleteProcessing = (stateOn) => {
                state.isDeleteLoading = stateOn;
                confirmDeleteButton.disabled = stateOn;
                deleteSpinner.classList.toggle('hidden', !stateOn);
                confirmDeleteText.textContent = stateOn ? 'Deleting...' : (state.activeDeleteId ? 'Delete' : 'Delete');
            };

            const sendBulkStatusRequest = () => {
                const status = bulkStatusSelect.value;
                if (!status || state.selectedIds.size === 0) {
                    console.warn('Select at least one business before changing status.');
                    bulkStatusSelect.value = '';
                    return;
                }
                showBulkStatusProcessing(true);
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('status', status);
                state.selectedIds.forEach((id) => formData.append('selected_ids[]', id));

                fetch("{{ route('super-admin.businesses.bulk-status') }}", {
                        method: 'POST',
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            bulkStatusSelect.value = '';
                            loadPageFromUrl(`${pageUrl}?${getQueryParameters().toString()}`);
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    })
                    .finally(() => showBulkStatusProcessing(false));
            };

            const sendDeleteRequest = (isBulk) => {
                if (isBulk && state.selectedIds.size === 0) {
                    console.warn('Select businesses before deleting.');
                    return;
                }
                showDeleteProcessing(true);
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('_method', 'DELETE');
                if (isBulk) {
                    state.selectedIds.forEach((id) => formData.append('selected_ids[]', id));
                } else {
                    formData.append('business_id', state.activeDeleteId);
                }

                fetch("{{ route('super-admin.businesses.destroy') }}", {
                        method: 'POST',
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            deleteModal.classList.add('hidden');
                            loadPageFromUrl(`${pageUrl}?${getQueryParameters().toString()}`);
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                    })
                    .finally(() => showDeleteProcessing(false));
            };

            searchInput.addEventListener('input', debounce(() => {
                fetchAndUpdateTable(getQueryParameters());
            }, 500));

            statusFilter.addEventListener('change', () => fetchAndUpdateTable(getQueryParameters()));
            typeFilter.addEventListener('change', () => fetchAndUpdateTable(getQueryParameters()));
            if (clearFiltersButton) {
                clearFiltersButton.addEventListener('click', () => {
                    searchInput.value = '';
                    statusFilter.value = '';
                    typeFilter.value = '';
                    loadPageFromUrl(pageUrl);
                });
            }

            bulkStatusSelect.addEventListener('change', sendBulkStatusRequest);
            bulkDeleteButton.addEventListener('click', function() {
                if (state.selectedIds.size === 0) {
                    console.warn('Select at least one business to delete.');
                    return;
                }
                state.activeDeleteId = null;
                state.activeDeleteName = `${state.selectedIds.size} selected businesses`;
                deleteModalMessage.textContent = `Are you sure you want to delete ${state.selectedIds.size} selected businesses? This action cannot be undone.`;
                deleteModal.classList.remove('hidden');
            });
            clearSelectionButton.addEventListener('click', resetSelection);
            cancelDeleteButton.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
            confirmDeleteButton.addEventListener('click', function() {
                sendDeleteRequest(state.activeDeleteId === null);
            });

            const handleTableWrapperClick = function(event) {
                const link = event.target.closest('a');
                if (link && link.closest('.pagination')) {
                    event.preventDefault();
                    loadPageFromUrl(link.href);
                }
            };

            tableWrapper.removeEventListener('click', handleTableWrapperClick);
            tableWrapper.addEventListener('click', handleTableWrapperClick);

            bindRowCheckboxes();
            bindSelectAllCheckbox();
            bindDeleteButtons();
            bindPaginationLinks();
        });
    </script>
</x-layouts.super-admin>