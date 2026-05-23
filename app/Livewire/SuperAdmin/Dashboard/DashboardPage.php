<?php

namespace App\Livewire\SuperAdmin\Dashboard;

use App\Models\Business;
use App\Models\User;
use Livewire\Component;

class DashboardPage extends Component
{
    public function getStatsProperty(): array
    {
        return [
            'total_businesses'    => Business::count(),
            'active_businesses'   => Business::where('status', 'active')->count(),
            'total_staff'         => User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['super_admin']))->count(),
            'total_users'         => User::count(),
            'pending_businesses'  => Business::where('status', 'pending')->count(),
            'suspended_businesses'=> Business::where('status', 'suspended')->count(),
        ];
    }

    public function getRecentBusinessesProperty()
    {
        return Business::with('owner')->latest()->limit(5)->get();
    }

    public function getBusinessTypeStatsProperty(): array
    {
        return Business::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard.dashboard-page', [
            'stats' => $this->stats,
            'recentBusinesses' => $this->recentBusinesses,
            'businessTypeStats' => $this->businessTypeStats,
        ])->layout('layouts.super-admin', ['title' => 'Dashboard']);
    }
}
