<?php

namespace App\Livewire\Organization\Manufacturer;

use App\Models\Brand;
use App\Models\Organization;
use Illuminate\Support\Once;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManufacturerComponent extends Component
{
    use WithFileUploads;
    public $brand_id = '';
    public $brand_name;
    public $brand_image;
    public $brand_is_active;
    public $existing_brand_image;
    public $new_brand_image;

    public $brand;

    // Validation rules
    protected $rules = [
        'brand_name' => 'required|min:3|max:25|unique:categories,brand_name',
    ];

    // Custom validation messages (optional)
    protected $messages = [
        'brand_name.required' => 'The category name is required.',
    ];

    public function createManufacturer()
    {
        $this->validate([
            'brand_name' => 'required|string|max:255',
        ]);
        $image_url = '';
        $organizationId = auth()->user()->organization_id;
        if($this->new_brand_image){
            $image_url = $this->new_brand_image->store('brand_images', 'public');
        }

        // Create the category
        Brand::create([
            'organization_id' => $organizationId,
            'brand_name' => $this->brand_name,
            'brand_image' => $image_url,
        ]);

        $this->reset();
        $this->dispatch('close-modal', 'add-manufacturer-modal');
        $this->dispatch('pg:eventRefresh-manufacturer-list-uxq1na-table');
    }

    #[On('edit-brand')]
    public function edit($rowId)
    {
        $brand = Brand::findOrFail($rowId);
        $this->brand = $brand;
        $this->brand_id = $brand->id;
        $this->brand_name = $brand->brand_name;
        $this->existing_brand_image = $brand->brand_image;
        $this->dispatch('open-modal', 'edit-manufacturer-modal');
    }

    public function updateManufacturer()
    {
        $this->validate([
            'brand_name' => 'required|string|max:255',
        ]);

        $brand = Brand::findOrFail($this->brand_id);

        // Define image path to use for update
        $imagePath = $this->brand_image; 

        if ($this->new_brand_image) {
            // Store new image and update the path
            $imagePath = $this->new_brand_image->store('brand_images', 'public');
        }

        $brand->update([
            'brand_name' => $this->brand_name,
            'brand_image' => $imagePath,
        ]);

        $this->dispatch('close-modal', 'edit-manufacturer-modal');
        $this->dispatch('pg:eventRefresh-manufacturer-list-uxq1na-table');
    }

    public function deleteBrand()
    {
        $brand = Brand::findOrFail($this->brand_id);
        $brand->delete();
        $this->dispatch('pg:eventRefresh-category-list-a1ujvr-table');
        $this->dispatch('close-modal', 'edit-manufacturer-modal');
    }
    public function render()
    {
        return view('livewire.organization.manufacturer.manufacturer-component');
    }
}
