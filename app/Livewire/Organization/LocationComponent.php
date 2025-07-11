<?php

namespace App\Livewire\Organization;

use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class LocationComponent extends Component
{
    public $id = '';
    public $name = '';
    public $nick_name = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $country = '';
    public $pin = '';

    public $is_active = true;
    public $is_delete = false;

    public $created_by = '';

    public $locationId = '';

    public $notifications = [];


    public $countries = [
        'USA' => [
            'Alabama',
            'Alaska',
            'Arizona',
            'Arkansas',
            'California',
            'Colorado',
            'Connecticut',
            'Delaware',
            'Florida',
            'Georgia',
            'Hawaii',
            'Idaho',
            'Illinois',
            'Indiana',
            'Iowa',
            'Kansas',
            'Kentucky',
            'Louisiana',
            'Maine',
            'Maryland',
            'Massachusetts',
            'Michigan',
            'Minnesota',
            'Mississippi',
            'Missouri',
            'Montana',
            'Nebraska',
            'Nevada',
            'New Hampshire',
            'New Jersey',
            'New Mexico',
            'New York',
            'North Carolina',
            'North Dakota',
            'Ohio',
            'Oklahoma',
            'Oregon',
            'Pennsylvania',
            'Rhode Island',
            'South Carolina',
            'South Dakota',
            'Tennessee',
            'Texas',
            'Utah',
            'Vermont',
            'Virginia',
            'Washington',
            'West Virginia',
            'Wisconsin',
            'Wyoming'
        ],
        'England' => [
            'Bedfordshire',
            'Berkshire',
            'Bristol',
            'Buckinghamshire',
            'Cambridgeshire',
            'Cheshire',
            'Cornwall',
            'Cumbria',
            'Derbyshire',
            'Devon',
            'Dorset',
            'Durham',
            'Essex',
            'Gloucestershire',
            'Greater London',
            'Greater Manchester',
            'Hampshire',
            'Herefordshire',
            'Hertfordshire',
            'Kent',
            'Lancashire',
            'Leicestershire',
            'Lincolnshire',
            'Merseyside',
            'Norfolk',
            'North Yorkshire',
            'Northamptonshire',
            'Northumberland',
            'Nottinghamshire',
            'Oxford',
            'Rutland',
            'Shropshire',
            'Somerset',
            'South Yorkshire',
            'Staffordshire',
            'Suffolk',
            'Surrey',
            'Tyne and Wear',
            'Warwickshire',
            'West Midlands',
            'West Sussex',
            'West Yorkshire',
            'Wiltshire'
        ],
        'Canada' => [
            'Alberta',
            'British Columbia',
            'Manitoba',
            'New Brunswick',
            'Newfoundland and Labrador',
            'Nova Scotia',
            'Ontario',
            'Prince Edward Island',
            'Quebec',
            'Saskatchewan'
        ],
        'India' => [
            'Andhra Pradesh',
            'Arunachal Pradesh',
            'Assam',
            'Bihar',
            'Chhattisgarh',
            'Goa',
            'Gujarat',
            'Haryana',
            'Himachal Pradesh',
            'Jharkhand',
            'Karnataka',
            'Kerala',
            'Madhya Pradesh',
            'Maharashtra',
            'Manipur',
            'Meghalaya',
            'Mizoram',
            'Nagaland',
            'Odisha',
            'Punjab',
            'Rajasthan',
            'Sikkim',
            'Tamil Nadu',
            'Telangana',
            'Tripura',
            'Uttar Pradesh',
            'Uttarakhand',
            'West Bengal'
        ]
    ];

    public $selectedCountry;
    public $states = [];

    public function mount($location = null)
    {
        
        if ($location) {
            $this->selectedCountry = $location->country;
            $this->state = $location->state;
            $this->states = $this->countries[$this->selectedCountry] ?? [];
        }
        if(Location::where('org_id', auth()->user()->organization_id)->where('is_active',true)->count() == 0) {
            // Use JavaScript to defer the modal opening
            $this->dispatch('openAddLocationModalDeferred');
        }
    }

    public function updatedSelectedCountry($country)
    {
        $this->states = $this->countries[$country] ?? [];
    }

    public function createLocation()
    {

        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('add_locations') && $user->role_id > 2) {
            $this->addNotification('You don\'t have permission to add locations!', 'error');
            return;
        }
        // Validate input fields
        $this->validate([
            'name' => 'required|string|max:255',
            'nick_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'selectedCountry' => 'required|string|max:255',
            'pin' => 'required|string|max:10',
        ]);
        // Add custom validation logic for 'name' uniqueness based on 'is_active'
        if (
            Location::where('name', $this->name)
                ->where('is_active', true)
                ->exists()
        ) {
            $this->addError('name', 'The name must be unique among locations.');
            return;
        }
        if (
            Location::where('nick_name', $this->name)
                ->where('is_active', true)
                ->exists()
        ) {
            $this->addError('nick_name', 'The Nick name must be unique among locations.');
            return;
        }
        // Create the location
        $location = Location::create([
            'name' => $this->name,
            'nick_name' => $this->nick_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->selectedCountry,
            'pin' => $this->pin,
            'is_deleted' => false,
            'org_id' => auth()->user()->organization_id,
            'is_active' => true,
            'created_by' => auth()->user()->id,
        ]);

        $auditService = app(\App\Services\UserLocationAuditService::class);
        $auditService->logLocationCreate(
            $location
        );

        // Optional: Clear form fields after saving
        $this->reset();

        // Optional: Provide feedback to the user
        $this->dispatch('pg:eventRefresh-locations-list-iol5e9-table');
        $this->dispatch('close-modal', 'add-location-modal');
    }

    #[On('edit-location')]
    public function startEdit($rowId)
    {
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('edit_locations') && $user->role_id > 2) {
            $this->addNotification('You don\'t have permission to edit locations!', 'error');
            return;
        }
        
        $this->reset();
        $this->editing = true;

        $this->locationId = $rowId;

        $location = Location::findOrFail($rowId);

        $this->name = $location->name;
        $this->nick_name = $location->nick_name;
        $this->phone = $location->phone;
        $this->email = $location->email;
        $this->address = $location->address;
        $this->city = $location->city;
        $this->state = $location->state;
        $this->country = $location->country;
        $this->pin = $location->pin;
        $this->is_active = $location->is_active;
        $this->description = $location->description;
        $this->is_active = $location->is_active;

        // Set country first
        $this->selectedCountry = $location->country;
        // $this->states = $this->countries[$location->country] ?? [];

        $this->state = $location->state;

        $this->states = $this->countries[$location->country] ?? [];

        
        $this->dispatch('open-modal', 'edit-location-modal');
        Log::info($this->state);
    }

    public function updateLocation()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nick_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'selectedCountry' => 'required|string|max:255',
            'pin' => 'required|string|max:10',
        ]);
        // Check for unique constraints on 'name' and 'nick_name'
        if (
            Location::where('name', $this->name)
                ->where('is_active', true)
                ->where('id', '!=', $this->locationId) 
                ->where('org_id',auth()->user()->organization->id)
                ->exists()
        ) {
            $this->addError('name', 'The name must be unique among locations.');
            return;
        }
        if (
            Location::where('nick_name', $this->name)
                ->where('is_active', true)
                ->where('id', '!=', $this->locationId) 
                ->exists()
        ) {
            $this->addError('nick_name', 'The Nick name must be unique among locations.');
            return;
        }

        // Find and update the location
        $location = Location::findOrFail($this->locationId);
        $oldValues = $location->toArray();
        $location->update([
            'name' => $this->name,
            'nick_name' => $this->nick_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->selectedCountry,
            'pin' => $this->pin,
            'is_active' => $this->is_active,
        ]);
        $auditService = app(\App\Services\UserLocationAuditService::class);
        $auditService->logLocationUpdate($location, $oldValues);
        // Dispatch events and reset state

        $this->dispatch('close-modal', 'edit-location-modal');
        $this->dispatch('pg:eventRefresh-locations-list-iol5e9-table');
    }

    public function deleteLocation()
    {
        // Ensure a valid location ID exists
        $user = auth()->user();
        $role = $user->role;
        if (!$role?->hasPermission('delete_locations') &&$user->role_id > 2) {
            $this->addNotification('You don\'t have permission to delete locations!', 'error');
            return;
        }
        if (!$this->locationId) {
            $this->addNotification( 'No location selected to delete.','error');
            return;
        }

        // Attempt to delete the location
        $location = Location::find($this->locationId);

        if ($location) {
            $location->is_active = false;
            $location->save();
            $auditService = app(\App\Services\UserLocationAuditService::class);
            $auditService->logLocationDelete(
                $location
            );
            session()->flash('success', 'Location deleted successfully.');
        } else {
            session()->flash('error', 'Location not found.');
        }

        // Reset the form and close the modal
        $this->reset();
        $this->dispatch('close-modal', 'edit-location-modal');
        $this->dispatch('pg:eventRefresh-locations-list-iol5e9-table');
    }
    
    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ]);

        // Limit to a maximum of 3-5 notifications if needed
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        }));
    }

    public function render()
    {
        return view('livewire.organization.location-component');
    }
}