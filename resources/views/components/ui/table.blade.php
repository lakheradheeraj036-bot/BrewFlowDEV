@props([])

{{--
    Usage:
    <x-ui.table>
        <x-slot:head>
            <tr>
                <th>Name</th>
                ...
            </tr>
        </x-slot:head>
        <tr> ... </tr>
    </x-ui.table>
--}}

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-sm']) }}>
        @if(isset($head))
            <thead class="bg-slate-50 border-b border-slate-200">
                {{ $head }}
            </thead>
        @endif

        <tbody class="divide-y divide-slate-100">
            {{ $slot }}
        </tbody>

        @if(isset($foot))
            <tfoot class="bg-slate-50 border-t border-slate-200">
                {{ $foot }}
            </tfoot>
        @endif
    </table>
</div>
