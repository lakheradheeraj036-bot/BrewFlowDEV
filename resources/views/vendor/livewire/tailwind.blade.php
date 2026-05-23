@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-500">
                    Showing
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
                        of
                    @endif
                    <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>
            <div>
                <span class="relative z-0 inline-flex gap-1">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center px-3 py-2 text-sm text-slate-400 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed" aria-disabled="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="inline-flex items-center px-3 py-2 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                    @endif

                    {{-- Page numbers --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="inline-flex items-center px-3 py-2 text-sm text-slate-400">{{ $element }}</span>
                        @endif
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="inline-flex items-center px-3.5 py-2 text-sm font-semibold text-white bg-amber-500 border border-amber-500 rounded-xl" aria-current="page">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="inline-flex items-center px-3.5 py-2 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">{{ $page }}</button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="inline-flex items-center px-3 py-2 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <span class="inline-flex items-center px-3 py-2 text-sm text-slate-400 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed" aria-disabled="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Mobile --}}
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed">Previous</span>
            @else
                <button wire:click="previousPage" class="inline-flex items-center px-4 py-2 text-sm text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Previous</button>
            @endif
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" class="inline-flex items-center px-4 py-2 text-sm text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Next</button>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed">Next</span>
            @endif
        </div>
    </nav>
@endif
