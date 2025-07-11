<?php

namespace App\Livewire\Admin\Units;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Unit; // Assuming you have a Unit model
use Livewire\WithFileUploads;

class UnitsComponent extends Component
{
    use WithFileUploads; // For handling file uploads, if needed

    public $id = '';
    public $unit_name = '';
    public $unit_code = '';
    public $is_active =''; // Default to active
    public $is_deleted = false;

    public $unitId = ''; // Changed from $unitId to avoid conflicts

    protected $rules = [
        'unit_name' => 'required|string|max:255',
        'unit_code' => 'required|string|max:50',
    ];

    public function createUnit()
    {
        $this->validate();
        $this->validate([
            'unit_name' => 'unique:units,unit_name',
            'unit_code' => 'unique:units,unit_code',
        ]);

        Unit::create([
            'unit_name' => $this->unit_name,
            'unit_code' => strtoupper($this->unit_code),
            'is_active' => $this->is_active,
        ]);

        // Reset form data after creation
        $this->reset();
        $this->dispatch('pg:eventRefresh-unit-list-pmsemm-table');
        $this->dispatch('close-modal', 'add-unit-modal');
    }

    #[On('edit-unit')]
    public function startEdit($rowId)
    {
        $this->editing = true;
        $this->unitId = $rowId;

        $unit = Unit::findOrFail($rowId);
        $this->unit_name = $unit->unit_name;
        $this->unit_code = $unit->unit_code;
        $this->is_active = $unit->is_active;
        $this->dispatch('open-modal', 'edit-unit-modal');

    }

    public function updateUnit()
    {
        $this->validate();
        if (
            Unit::where('unit_name', $this->unit_name)
                ->where('is_active', true)
                ->where('id', '!=', $this->unitId)
                ->exists()
        ) {
            $this->addError('unit_name', 'The name must be unique.');
            return;
        }

        if (
            Unit::where('unit_code', $this->unit_code)
                ->where('is_active', true)
                ->where('id', '!=', $this->unitId)
                ->exists()
        ) {
            $this->addError('unit_code', 'The Unit Code is already taken.');
            return;
        }
        $unit = Unit::findOrFail($this->unitId);
        $unit->update([
            'unit_name' => $this->unit_name,
            'is_active' => $this->is_active,
            'unit_code' => strtoupper($this->unit_code),

        ]);

        // Reset form data after creation
        $this->reset();
        $this->dispatch('pg:eventRefresh-unit-list-pmsemm-table');
        $this->dispatch('close-modal', 'edit-unit-modal');
    }

    public function deleteUnit($rowId)
    {
        // Close the modal immediately after delete button is clicked
        $this->dispatch('close-modal', 'edit-unit-modal');

        // Attempt to find the unit
        $unit = Unit::find($rowId);

        if (!$unit) {
            session()->flash('error', 'Unit not found.');
            return;
        }

        $user = auth()->user(); // Get the authenticated user

        if ($user->role_id == 1) { // Super Admin
            $unit->update(['is_active' => false, 'is_deleted' => true]);
        } elseif ($user->role_id == 2) { // Other Admins
            $unit->update(['is_active' => false]);
        }

        $this->dispatch('pg:eventRefresh-unit-list-pmsemm-table');
        session()->flash('success', 'Unit deleted successfully.');
        $this->reset();
    }



    public function render()
    {
        $units = Unit::all(); // Fetch all units from the database

        return view('livewire.admin.units.units-component', [
            'units' => $units, // Pass the units data to the view
        ]);
    }
}

