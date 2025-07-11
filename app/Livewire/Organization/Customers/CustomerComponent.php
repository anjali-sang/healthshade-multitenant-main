<?php

namespace App\Livewire\Organization\Customers;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Customer;
use Illuminate\Validation\Rule;

class CustomerComponent extends Component
{
    public $customers;
    public $customer_id;
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $customer_address;
    public $customer_city;
    public $customer_state;
    public $customer_pin_code;
    public $customer_country;
    public $customer_is_active = true;

    public $showAddModal = false;
    public $showEditModal = false;
    public $isEditing = false;

    public $selectedCountry = null;
    public $states = [];

    public $countries = [
        'USA' => [
            'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado',
            'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho',
            'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
            'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
            'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada',
            'New Hampshire', 'New Jersey', 'New Mexico', 'New York',
            'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon',
            'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota',
            'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington',
            'West Virginia', 'Wisconsin', 'Wyoming'
        ],
        'United Kingdom' => [
            'England', 'Scotland', 'Wales', 'Northern Ireland'
        ],
        'Canada' => [
            'Alberta', 'British Columbia', 'Manitoba', 'New Brunswick',
            'Newfoundland and Labrador', 'Nova Scotia', 'Ontario',
            'Prince Edward Island', 'Quebec', 'Saskatchewan',
            'Northwest Territories', 'Nunavut', 'Yukon'
        ],
        'India' => [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
            'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
            'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
            'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan',
            'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh',
            'Uttarakhand', 'West Bengal', 'Delhi', 'Jammu and Kashmir', 'Ladakh'
        ],
        'Australia' => [
            'Australian Capital Territory', 'New South Wales', 'Northern Territory',
            'Queensland', 'South Australia', 'Tasmania', 'Victoria', 'Western Australia'
        ]
    ];

    protected function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => [
                'required',
                'email',
                'max:255',
                $this->customer_id
                    ? Rule::unique('customers', 'customer_email')->ignore($this->customer_id)
                    : Rule::unique('customers', 'customer_email'),
            ],
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'customer_city' => 'nullable|string|max:100',
            'customer_state' => 'nullable|string|max:100',
            'customer_pin_code' => 'nullable|string|max:20',
            'selectedCountry' => 'nullable|string|max:100',
            'customer_is_active' => 'boolean',
        ];
    }

    protected $messages = [
        // Customer Name
        'customer_name.required' => 'Customer name is required.',
        'customer_name.string' => 'Customer name must be a valid string.',
        'customer_name.max' => 'Customer name may not be greater than 255 characters.',

        // Email
        'customer_email.required' => 'Email address is required.',
        'customer_email.email' => 'Please enter a valid email address.',
        'customer_email.max' => 'Email address may not be greater than 255 characters.',
        'customer_email.unique' => 'This email address is already taken.',

        // Phone
        'customer_phone.string' => 'Phone number must be a valid string.',
        'customer_phone.max' => 'Phone number may not be greater than 20 characters.',

        // Address
        'customer_address.string' => 'Address must be a valid string.',
        'customer_address.max' => 'Address may not be greater than 500 characters.',

        // City
        'customer_city.string' => 'City must be a valid string.',
        'customer_city.max' => 'City may not be greater than 100 characters.',

        // State
        'customer_state.string' => 'State must be a valid string.',
        'customer_state.max' => 'State may not be greater than 100 characters.',

        // Pin Code
        'customer_pin_code.string' => 'Pin code must be a valid string.',
        'customer_pin_code.max' => 'Pin code may not be greater than 20 characters.',

        // Country
        'selectedCountry.string' => 'Country must be a valid string.',
        'selectedCountry.max' => 'Country may not be greater than 100 characters.',

        // Active Status
        'customer_is_active.boolean' => 'Active status must be true or false.',
    ];

    public function updatedSelectedCountry($country)
    {
        $this->states = $this->countries[$country] ?? [];
        // Reset state when country changes
        $this->customer_state = null;
    }

    public function mount()
    {
        $this->loadCustomers();
    }

    public function loadCustomers()
    {
        $this->customers = Customer::latest()->get();
    }

    public function openCustomerModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->dispatch('open-modal', 'customer-modal');
    }

    #[On('openEditModal')]
    public function openEditModal($customerId)
    {
        $this->resetForm();
        $customer = Customer::findOrFail($customerId);
        
        $this->isEditing = true;
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->customer_name;
        $this->customer_email = $customer->customer_email;
        $this->customer_phone = $customer->customer_phone;
        $this->customer_address = $customer->customer_address;
        $this->customer_city = $customer->customer_city;
        $this->customer_state = $customer->customer_state;
        $this->customer_pin_code = $customer->customer_pin_code;
        $this->customer_country = $customer->customer_country;
        $this->customer_is_active = (bool) $customer->customer_is_active;
        
        // Set the selected country and load states
        $this->selectedCountry = $customer->customer_country;
        $this->updatedSelectedCountry($customer->customer_country);
        
        $this->dispatch('open-modal', 'customer-modal');
    }

    public function resetForm()
    {
        $this->customer_id = null;
        $this->customer_name = '';
        $this->customer_email = '';
        $this->customer_phone = '';
        $this->customer_address = '';
        $this->customer_city = '';
        $this->customer_state = '';
        $this->customer_pin_code = '';
        $this->customer_country = '';
        $this->selectedCountry = null;
        $this->states = [];
        $this->customer_is_active = true;
        $this->resetErrorBag();
    }

    public function closeModals()
    {
        $this->dispatch('close-modal', 'customer-modal');
        $this->isEditing = false;
        $this->resetForm();
        $this->dispatch('pg:eventRefresh-customer-list-l0gx9s-table');
    }

    public function save()
    {
        $this->validate();

        try {
            Customer::create([
                'customer_name' => $this->customer_name,
                'customer_email' => $this->customer_email,
                'customer_phone' => $this->customer_phone,
                'customer_address' => $this->customer_address,
                'customer_city' => $this->customer_city,
                'customer_state' => $this->customer_state,
                'customer_pin_code' => $this->customer_pin_code,
                'customer_country' => $this->selectedCountry,
                'customer_is_active' => $this->customer_is_active,
            ]);

            $this->loadCustomers();
            $this->closeModals();

            session()->flash('success', 'Customer added successfully!');
            $this->dispatch('customer-saved');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding customer: ' . $e->getMessage());
            \Log::error('Customer creation failed: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $customer = Customer::findOrFail($this->customer_id);
            $customer->update([
                'customer_name' => $this->customer_name,
                'customer_email' => $this->customer_email,
                'customer_phone' => $this->customer_phone,
                'customer_address' => $this->customer_address,
                'customer_city' => $this->customer_city,
                'customer_state' => $this->customer_state,
                'customer_pin_code' => $this->customer_pin_code,
                'customer_country' => $this->selectedCountry,
                'customer_is_active' => $this->customer_is_active,
            ]);

            $this->loadCustomers();
            $this->closeModals();

            session()->flash('success', 'Customer updated successfully!');
            $this->dispatch('customer-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating customer: ' . $e->getMessage());
            \Log::error('Customer update failed: ' . $e->getMessage());
        }
    }

    public function delete($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);
            $customer->delete();
            
            $this->loadCustomers();
            session()->flash('success', 'Customer deleted successfully!');
            $this->dispatch('customer-deleted');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting customer: ' . $e->getMessage());
            \Log::error('Customer deletion failed: ' . $e->getMessage());
        }
    }

    public function toggleCustomerStatus($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);
            $customer->update([
                'customer_is_active' => !$customer->customer_is_active
            ]);
            
            $this->loadCustomers();
            $status = $customer->customer_is_active ? 'activated' : 'deactivated';
            session()->flash('success', "Customer {$status} successfully!");
            $this->dispatch('customer-status-changed');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating customer status: ' . $e->getMessage());
            \Log::error('Customer status update failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.organization.customers.customer-component');
    }
}