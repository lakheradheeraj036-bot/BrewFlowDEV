@props(['id' => null, 'title' => null, 'maxWidth' => 'lg'])

@php
$maxWidths = [
    'sm'  => 'max-w-sm',
    'md'  => 'max-w-md',
    'lg'  => 'max-w-lg',
    'xl'  => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];
$maxWidthClass = $maxWidths[$maxWidth] ?? $maxWidths['lg'];
@endphp

<div
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="show = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Modal panel --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full {{ $maxWidthClass }} bg-white rounded-2xl shadow-xl border border-slate-200"
    >
        {{-- Modal header --}}
        @if($title)
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">{{ $title }}</h3>
                <button
                    @click="show = false"
                    class="text-slate-400 hover:text-slate-600 transition-colors rounded-lg p-1 hover:bg-slate-100"
                    aria-label="Close modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Modal body --}}
        <div class="p-6">{{ $slot }}</div>

        {{-- Optional footer slot --}}
        @if(isset($footer))
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 rounded-b-2xl">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
