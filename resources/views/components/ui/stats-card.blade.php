@props([
    'label',
    'value',
    'icon',
    'color'   => 'amber',
    'trend'   => null,
    'trendUp' => true,
])

@php
$colors = [
    'amber'  => ['icon' => 'bg-amber-500',   'shadow' => 'shadow-amber-500/20'],
    'blue'   => ['icon' => 'bg-blue-500',    'shadow' => 'shadow-blue-500/20'],
    'green'  => ['icon' => 'bg-emerald-500', 'shadow' => 'shadow-emerald-500/20'],
    'red'    => ['icon' => 'bg-red-500',     'shadow' => 'shadow-red-500/20'],
    'purple' => ['icon' => 'bg-purple-500',  'shadow' => 'shadow-purple-500/20'],
];
$c = $colors[$color] ?? $colors['amber'];
@endphp

<div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="text-3xl font-bold text-slate-800 mt-2 tracking-tight">{{ $value }}</p>

            @if($trend)
                <div class="flex items-center gap-1 mt-3">
                    @if($trendUp)
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"/>
                        </svg>
                        <span class="text-xs font-medium text-emerald-600">{{ $trend }}</span>
                    @else
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"/>
                        </svg>
                        <span class="text-xs font-medium text-red-600">{{ $trend }}</span>
                    @endif
                    <span class="text-xs text-slate-400">vs last month</span>
                </div>
            @endif
        </div>

        {{-- Icon container --}}
        <div class="{{ $c['icon'] }} {{ $c['shadow'] }} w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm ml-4">
            {{ $icon }}
        </div>
    </div>
</div>
