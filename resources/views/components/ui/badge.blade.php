@props(['color' => 'gray', 'size' => 'sm'])

@php
$palettes = [
    'green'  => ['ring' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200', 'dot' => 'bg-emerald-500'],
    'red'    => ['ring' => 'bg-red-50 text-red-700 ring-1 ring-red-200',             'dot' => 'bg-red-500'],
    'yellow' => ['ring' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',       'dot' => 'bg-amber-500'],
    'amber'  => ['ring' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',       'dot' => 'bg-amber-500'],
    'blue'   => ['ring' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',          'dot' => 'bg-blue-500'],
    'gray'   => ['ring' => 'bg-slate-50 text-slate-700 ring-1 ring-slate-200',       'dot' => 'bg-slate-400'],
    'purple' => ['ring' => 'bg-purple-50 text-purple-700 ring-1 ring-purple-200',    'dot' => 'bg-purple-500'],
    'orange' => ['ring' => 'bg-orange-50 text-orange-700 ring-1 ring-orange-200',    'dot' => 'bg-orange-500'],
];
$p = $palettes[$color] ?? $palettes['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {$p['ring']}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $p['dot'] }} opacity-80 flex-shrink-0"></span>
    {{ $slot }}
</span>
