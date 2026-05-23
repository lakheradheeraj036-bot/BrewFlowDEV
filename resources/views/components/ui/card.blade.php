@props([
    'title'       => null,
    'description' => null,
    'padding'     => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-200']) }}>
    {{-- Card header (rendered only when title or action slot are present) --}}
    @if($title || isset($action))
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="min-w-0">
                @if($title)
                    @if($title instanceof \Illuminate\View\ComponentSlot)
                        <div class="text-base font-semibold text-slate-800">{!! $title !!}</div>
                    @else
                        <h3 class="text-base font-semibold text-slate-800">{{ $title }}</h3>
                    @endif
                @endif
                @if($description)
                    <p class="text-sm text-slate-500 mt-0.5">{{ $description }}</p>
                @endif
            </div>
            @if(isset($action))
                <div class="flex-shrink-0 ml-4">{{ $action }}</div>
            @endif
        </div>
    @endif

    {{-- Card body --}}
    <div @class(['p-6' => $padding])>
        {{ $slot }}
    </div>
</div>
