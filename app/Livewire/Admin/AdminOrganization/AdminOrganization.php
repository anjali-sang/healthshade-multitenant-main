<?php

namespace App\Livewire\Admin\AdminOrganization;

use App\Models\Organization;
use App\Models\Location;
use App\Models\Subscription;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminOrganization extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $subscription_id = '';
    public $address = '';
    public $organization_id = '';

    public $is_active = '';
    public $subscriptions = [];

    public function mount($id = null)
    {
        if ($id) {
            $this->organization_id = $id;
            $this->loadOrganizationData();
        }
        $this->subscriptions = Subscription::all();
    }

    public function loadOrganizationData()
    {
        $organization = Organization::find($this->organization_id);
        if ($organization) {
            $this->name = $organization->name;
            $this->email = $organization->email;
            $this->phone = $organization->phone;
            $this->location_id = $organization->location_id;
            $this->subscription_id = $organization->subscription_id;
            $this->address = $organization->address;
            $this->is_active = $organization->is_active; // Load active status

        }
    }

    #[On('edit-organization')]
    public function startEdit($rowId)
    {
        $this->organization_id = $rowId;
        $this->loadOrganizationData();

        // Open the modal
        $this->dispatch('open-modal', 'edit-organization-modal');
    }

    public function updateOrganization()
    {
        // Validate the required fields
        $this->validate([
            'name' => 'required|string|max:255',
            'subscription_id' => 'required|exists:subscriptions,id',
            'phone' => 'nullable|string|max:15',
            'address' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Check if the organization name is unique, excluding the current organization
        if (
            Organization::where('name', $this->name)
                ->where('is_active', true)
                ->where('id', '!=', $this->organization_id)
                ->exists()
        ) {
            $this->addError('name', 'The organization name must be unique.');
            return;
        }

        if (
            Organization::where('email', $this->email)
                ->where('is_active', true)
                ->where('id', '!=', $this->organization_id)
                ->exists()
        ) {
            $this->addError('email', 'The email is already taken.');
            return;
        }

        // Proceed with updating the organization
        $organization = Organization::findOrFail($this->organization_id);
        $organization->update([
            'name' => $this->name,
            'subscription_id' => $this->subscription_id,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => (bool) $this->is_active,
        ]);

        $this->dispatch('pg:eventRefresh-organization-list-eyipxp-table');
        $this->dispatch('close-modal', 'edit-organization-modal');
        $this->reset();
    }

    public function deleteOrganization()
    {
        $organization = Organization::find($this->organization_id);

        if (!$organization) {
            session()->flash('error', 'Organization not found!');
            return;
        }

        $user = auth()->user(); // Get the authenticated user

        if ($user->role_id == 1) { // Super Admin
            $organization->update(['is_active' => false, 'is_deleted' => true]);
        } elseif ($user->role_id == 2) { // Other Admins
            $organization->update(['is_active' => false]);
        }

        $this->reset();
        $this->dispatch('close-modal', 'edit-organization-modal');
        session()->flash('success', 'Organization deleted successfully!');
        $this->dispatch('pg:eventRefresh-organization-list-eyipxp-table');
    }

    public function render()
    {
        $locations = Location::where('is_active', true)->where('org_id', auth()->user()->organization_id)->get();

        $subscriptions = Subscription::where('is_active', true)->get();

        return view('livewire.admin.admin-organization.admin-organization', compact('locations','subscriptions'));
    }
}
