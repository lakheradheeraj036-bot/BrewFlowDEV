@props([
    'variant' => 'primary',
    'size'    => 'md',
    'type'    => 'button',
    'href'    => null,
    'loading' => false,
])

@php
$variants = [
    'primary'   => 'bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-white shadow-sm shadow-amber-500/20 focus:ring-amber-500/30',
    'secondary' => 'bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 focus:ring-slate-300',
    'danger'    => 'bg-red-500 hover:bg-red-600 active:bg-red-700 text-white shadow-sm shadow-red-500/20 focus:ring-red-500/30',
    'outline'   => 'border border-slate-300 hover:border-slate-400 bg-white text-slate-700 focus:ring-slate-300',
    'ghost'     => 'hover:bg-slate-100 active:bg-slate-200 text-slate-600 focus:ring-slate-300',
    'success'   => 'bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white shadow-sm shadow-emerald-500/20 focus:ring-emerald-500/30',
];
$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs rounded-lg gap-1',
    'sm' => 'px-3 py-2 text-sm rounded-lg gap-1.5',
    'md' => 'px-4 py-2.5 text-sm rounded-xl gap-2',
    'lg' => 'px-5 py-3 text-base rounded-xl gap-2.5',
];
$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass    = $sizes[$size]    ?? $sizes['md'];
$tag          = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if(!$href) type="{{ $type }}" @endif
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center font-medium transition-all duration-150 focus:outline-none focus:ring-2 disabled:opacity-60 disabled:cursor-not-allowed {$variantClass} {$sizeClass}"]) }}
>
    @if($loading)
        <svg class="animate-spin w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    {{ $slot }}
</{{ $tag }}>
