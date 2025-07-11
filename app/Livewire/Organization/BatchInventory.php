<?php

namespace App\Livewire\Organization;

use App\Models\Location;
use Livewire\Component;

class BatchInventory extends Component
{

    public $selectedLocation = null;
    protected $queryString = ['selectedLocation'];
    public $locations = [];
    public function updatedSelectedLocation()
    {
        $this->dispatch('batchinventoryIocationChanged', $this->selectedLocation);
    }

    public function mount()
    {
        $this->locations = Location::where('org_id', auth()->user()->organization_id)->where('is_active', true)->get();
        $this->selectedLocation = auth()->user()->location_id ?? null;
    }
    public function render()
    {
        return view('livewire.organization.batch-inventory');
    }
}
