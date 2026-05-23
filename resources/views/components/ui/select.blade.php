@props(['label' => null, 'error' => null, 'hint' => null])

<div>
    @if($label)
        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
    @endif

    <div class="relative">
        <select
            {{ $attributes->merge([
                'class' => implode(' ', [
                    'w-full px-3 py-2.5 pr-10 text-sm border rounded-xl bg-white text-slate-800',
                    'transition-all focus:outline-none focus:ring-2 appearance-none',
                    $error
                        ? 'border-red-300 focus:border-red-400 focus:ring-red-200'
                        : 'border-slate-300 focus:border-amber-400 focus:ring-amber-200',
                ])
            ]) }}
        >
            {{ $slot }}
        </select>

        {{-- Custom chevron --}}
        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    @if($error)
        <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $error }}
        </p>
    @elseif($hint)
        <p class="text-xs text-slate-400 mt-1.5">{{ $hint }}</p>
    @endif
</div>
