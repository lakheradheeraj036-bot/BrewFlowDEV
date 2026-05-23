<div>
    <x-super-admin.breadcrumb :items="[['label' => 'Dashboard']]"/>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card label="Total Businesses" :value="$stats['total_businesses']" color="amber" trend="+12%" :trendUp="true">
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </x-slot:icon>
        </x-ui.stats-card>

        <x-ui.stats-card label="Active Businesses" :value="$stats['active_businesses']" color="green" trend="+8%" :trendUp="true">
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </x-slot:icon>
        </x-ui.stats-card>

        <x-ui.stats-card label="Total Staff" :value="$stats['total_staff']" color="blue" trend="+23%" :trendUp="true">
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </x-slot:icon>
        </x-ui.stats-card>

        <x-ui.stats-card label="Pending Approvals" :value="$stats['pending_businesses']" color="red" :trend="$stats['pending_businesses'].' new'" :trendUp="false">
            <x-slot:icon>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </x-slot:icon>
        </x-ui.stats-card>
    </div>

    {{-- Main content grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Businesses table (2/3 width) --}}
        <div class="lg:col-span-2">
            <x-ui.card title="Recent Businesses" description="Latest businesses registered on the platform">
                <x-slot:action>
                    <x-ui.button href="{{ route('super-admin.businesses.index') }}" variant="outline" size="sm" wire:navigate>
                        View all
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </x-ui.button>
                </x-slot:action>

                <x-ui.table>
                    <x-slot:head>
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Business</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">City</th>
                        </tr>
                    </x-slot:head>

                    @forelse($recentBusinesses as $business)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-amber-700 text-xs font-bold">{{ substr($business->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">{{ $business->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $business->email ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-sm text-slate-600 capitalize">{{ $business->type }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <x-ui.badge :color="$business->status_color">{{ ucfirst($business->status) }}</x-ui.badge>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-sm text-slate-600">{{ $business->city ?? '—' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-slate-400">No businesses yet</td>
                        </tr>
                    @endforelse
                </x-ui.table>
            </x-ui.card>
        </div>

        {{-- Right sidebar panels (1/3 width) --}}
        <div class="space-y-6">

            {{-- Business Types distribution --}}
            <x-ui.card title="Business Types" description="Distribution by category">
                <div class="space-y-4">
                    @php
                        $typeIcons = [
                            'cafe'       => '☕',
                            'hotel'      => '🏨',
                            'restaurant' => '🍽️',
                            'bar'        => '🍺',
                            'other'      => '🏢',
                        ];
                        $total = array_sum($businessTypeStats);
                    @endphp

                    @foreach($businessTypeStats as $type => $count)
                        @php $pct = $total > 0 ? round(($count / $total) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span>{{ $typeIcons[$type] ?? '🏢' }}</span>
                                    <span class="text-sm font-medium text-slate-700 capitalize">{{ $type }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-slate-500">{{ $count }}</span>
                                    <span class="text-xs text-slate-400 w-8 text-right">{{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-amber-500 h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach

                    @if(empty($businessTypeStats))
                        <p class="text-sm text-slate-400 text-center py-4">No data yet</p>
                    @endif
                </div>
            </x-ui.card>

            {{-- Quick Actions --}}
            <x-ui.card title="Quick Actions">
                <div class="space-y-2">
                    <a href="{{ route('super-admin.businesses.create') }}" wire:navigate
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 border border-slate-100 hover:border-slate-200 transition-all group">
                        <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Add Business</p>
                            <p class="text-xs text-slate-400">Register new business</p>
                        </div>
                    </a>

                    <a href="{{ route('super-admin.staff.index') }}" wire:navigate
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 border border-slate-100 hover:border-slate-200 transition-all group">
                        <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Invite Staff</p>
                            <p class="text-xs text-slate-400">Add team members</p>
                        </div>
                    </a>

                    <a href="{{ route('super-admin.settings.index') }}" wire:navigate
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 border border-slate-100 hover:border-slate-200 transition-all group">
                        <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700">Platform Settings</p>
                            <p class="text-xs text-slate-400">Configure your platform</p>
                        </div>
                    </a>
                </div>
            </x-ui.card>

        </div>
    </div>
</div>
