<?php

namespace App\Livewire\SuperAdmin\Businesses;

    use Livewire\Component;

class BusinessCreation extends Component
{
    public function render()
    {
        return view('livewire.super-admin.businesses.business-creation')
            ->layout('layouts.super-admin', ['title' => 'Add Business']);
    }
}
