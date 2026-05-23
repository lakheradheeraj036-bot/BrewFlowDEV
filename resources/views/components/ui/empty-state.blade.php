@props([
    'title'       => 'No data found',
    'description' => null,
    'icon'        => null,
])

<div class="flex flex-col items-center justify-center py-16 text-center">
    {{-- Icon container --}}
    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
        @if($icon)
            {{ $icon }}
        @else
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        @endif
    </div>

    <h3 class="text-base font-semibold text-slate-700 mb-1">{{ $title }}</h3>

    @if($description)
        <p class="text-sm text-slate-400 max-w-sm">{{ $description }}</p>
    @endif

    @if(isset($action))
        <div class="mt-6">{{ $action }}</div>
    @endif
</div>
