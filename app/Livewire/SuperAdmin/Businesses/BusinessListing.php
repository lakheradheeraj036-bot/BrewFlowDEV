<?php

namespace App\Livewire\SuperAdmin\Businesses;

use App\Models\Business;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessListing extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $typeFilter = '';

    public string $sortBy  = 'created_at';
    public string $sortDir = 'desc';

    public bool   $showDeleteModal     = false;
    public bool   $showBulkDeleteModal = false;
    public ?int   $deletingId          = null;
    public string $deletingName        = '';
    public bool   $isBulkDelete        = false;

    public array $selectedIds = [];
    public bool  $selectAll   = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }
    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        $this->selectedIds = $value
            ? Business::query()
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name',  'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('city',  'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->typeFilter,   fn($q) => $q->where('type',   $this->typeFilter))
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray()
            : [];
    }

    public function updatedSelectedIds(): void
    {
        $this->selectAll = false;
    }

    public function clearSelection(): void
    {
        $this->selectedIds = [];
        $this->selectAll   = false;
    }

    public function sort(string $column): void
    {
        $this->sortBy  = ($this->sortBy === $column)
            ? $this->sortBy
            : $column;
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'asc')
            ? 'desc'
            : 'asc';
        $this->sortBy  = $column;
    }

    public function confirmDelete(int $id, bool $isBulk = false): void
    {
        if ($isBulk) {
            if (!empty($this->selectedIds)) {
                $this->isBulkDelete    = true;
                $this->showDeleteModal = true;
            }
        } else {
            $business = Business::find($id);
            if ($business) {
                $this->isBulkDelete    = false;
                $this->deletingId      = $id;
                $this->deletingName    = $business->name;
                $this->showDeleteModal = true;
            }
        }
    }

    public function deleteBusiness(): void
    {
        if ($this->isBulkDelete) {
            $this->bulkDelete();
        } elseif ($this->deletingId) {
            Business::findOrFail($this->deletingId)->delete();
            $this->showDeleteModal = false;
            $this->deletingId      = null;
            $this->deletingName    = '';
            $this->selectedIds     = array_filter($this->selectedIds, fn($id) => (int)$id !== $this->deletingId);
            session()->flash('success', 'Business deleted successfully.');
        }
    }

    public function bulkDelete(): void
    {
        if (!empty($this->selectedIds)) {
            Business::whereIn('id', $this->selectedIds)->delete();
            $count             = count($this->selectedIds);
            $this->selectedIds = [];
            $this->selectAll   = false;
            $this->showDeleteModal = false;
            $this->isBulkDelete    = false;
            session()->flash('success', "{$count} " . str('business')->plural($count) . " deleted successfully.");
        }
    }

    public function bulkUpdateStatus(string $status): void
    {
        Business::whereIn('id', $this->selectedIds)->update(['status' => $status]);
        $count             = count($this->selectedIds);
        $this->selectedIds = [];
        $this->selectAll   = false;
        session()->flash('success', "{$count} " . str('business')->plural($count) . " updated to {$status}.");
    }

    public function updateStatus(int $id, string $status): void
    {
        Business::findOrFail($id)->update(['status' => $status]);
        session()->flash('success', 'Business status updated.');
    }

    public function render()
    {
        $businesses = Business::query()
            ->with('owner')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name',  'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('city',  'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->typeFilter,   fn($q) => $q->where('type',   $this->typeFilter))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(10);

        $stats = [
            'total'   => Business::count(),
            'active'  => Business::where('status', 'active')->count(),
            'pending' => Business::where('status', 'pending')->count(),
        ];

        return view('livewire.super-admin.businesses.business-listing', [
            'businesses' => $businesses,
            'stats'      => $stats,
        ])->layout('layouts.super-admin', ['title' => 'Businesses']);
    }
}
