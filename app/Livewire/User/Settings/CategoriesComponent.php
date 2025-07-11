<?php

namespace App\Livewire\User\Settings;
use Livewire\Attributes\On;

use App\Models\Category;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CategoriesComponent extends Component
{

    public $categoryId = '';

    public $category_name;
    public $category_description;
    public $organizations; // Holds the list of organizations

    public $is_active = false;

    // Validation rules
    protected $rules = [
        'category_name' => 'required|min:3|max:25|unique:categories,category_name',
        'category_description' => 'required|min:3|max:250',
        'is_active' => 'nullable|boolean',
    ];

    // Custom validation messages (optional)
    protected $messages = [
        'category_name.required' => 'The category name is required.',
        'category_name.unique' => 'This category name already exists.',
        'category_organization.required' => 'Please select an organization.',
    ];


    public function mount()
    {
        // Fetch organizations from the database
        $this->organizations = Organization::pluck('name', 'id');
    }
    public function createCategory()
    {
        // Validate the input
        $this->validate([
            'category_name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Fetch the organization_id from the authenticated user
        $organizationId = Auth::user()->organization_id;

        // Create the category
        $category = Category::create([
            'organization_id' => $organizationId,
            'category_name' => $this->category_name,
            'category_description' => $this->category_description,
            'is_active' => true,
        ]);

        $auditService = app(\App\Services\CategoryAuditing::class);
        $auditService->logDeletion(
            $category,
            'Added',
            'Category is added.'
        );

        // Reset the form fields
        $this->reset(['category_name', 'category_description', 'is_active']);

        // Close the modal
        $this->dispatch('close-modal', 'add-category-modal');
        $this->dispatch('pg:eventRefresh-category-list-a1ujvr-table');

    }

    #[On('edit')]

    public function edit($rowId)
    {
        $this->editing = true;
        $this->categoryId = $rowId;


        $category = Category::findOrFail($rowId); // Fetch category object

        $this->category_name = $category->category_name;
        // $this->category_organization = $category->organization_id;
        $this->category_description = $category->category_description;
        $this->is_active = $category->is_active;

        $this->dispatch('open-modal', 'edit-category-modal');
    }

    public function updateCategory()
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($this->categoryId);
        $oldCategory = clone $category; // clone before updating

        $category->update([
            'category_name' => $this->category_name,
            'category_description' => $this->category_description,
        ]);

        $this->dispatch('pg:eventRefresh-category-list-a1ujvr-table');
        $this->dispatch('close-modal', 'edit-category-modal');

        $auditService = app(\App\Services\CategoryAuditing::class);
        $auditService->logUpdate($oldCategory, $category, 'updated');
    }


    public function deleteCategory()
    {
        $category = Category::findOrFail($this->categoryId);
        $category->is_active = false;
        $category->save();

        $this->dispatch('pg:eventRefresh-category-list-a1ujvr-table');
        $this->dispatch('close-modal', 'edit-category-modal');

        $auditService = app(\App\Services\CategoryAuditing::class);
        $auditService->logDeletion(
            $category,
            'Removed',
            'Category marked as inactive.'
        );
    }


    public function render()
    {
        return view('livewire.user.settings.categories-component');
    }
}
