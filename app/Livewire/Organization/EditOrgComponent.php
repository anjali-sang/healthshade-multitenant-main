<?php

namespace App\Livewire\Organization;

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Organization;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class EditOrgComponent extends Component
{
    use WithFileUploads;
    public $id = '';
    public $name = '';
    public $subscription_id = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $state = '';
    public $city = '';
    public $country = '';
    public $pin = '';
    public $organization; 
    public $subscription_valid;

    public $selectedCountry;

    public $logo;



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
    public $states = [];
    public function mount($id = null)
    {
        $user = auth()->user();

        if ($user && $user->organization_id) {
            $this->organization = Organization::find($user->organization_id);

            if ($this->organization) {
                $this->name = $this->organization->name;
                $this->email = $this->organization->email;
                $this->subscription_id = $this->organization->subscription_id;
                $this->selectedCountry = $this->organization->country;
                $this->state = $this->organization->state;
                $this->city = $this->organization->city;
                $this->pin = $this->organization->pin;
                $this->address = $this->organization->address;
                $this->phone = $this->organization->phone;
                $this->states = $this->countries[$this->selectedCountry] ?? [];
                $this->subscription_valid = $this->organization->subscription_valid;
                $this->logo = json_decode($this->organization->image, true) ?? '';
            }
        }
    }

    public function updateOrganization()
    {
        if (!$this->organization) {
            session()->flash('error', 'Organization not found.');
            return null;
        }
    
        // Validate the form data
        $this->validate([
            'name' => ['required', 'min:3', 'max:25', Rule::unique('organizations', 'name')->ignore($this->organization->id)],
            'email' => 'required|email',
            'phone' => 'nullable|string|max:15',
            'selectedCountry' => 'required',
            'state' => 'required|string',
            'address' => 'required|string|max:255',
            'pin' => 'required|string|max:10',
            'logo' => 'nullable|image|max:2048', // Optional: if file validation needed
        ]);
    
        try {
            $this->country = $this->selectedCountry;
    
            $uploadedImage = $this->organization->image;
    
            // Handle image upload if a new one is selected
            if (!empty($this->logo)) {
                // Optionally delete old image here if needed
                $uploadedImage = $this->logo->store('organization_logos', 'public');
            }
    
            // Update the organization
            $this->organization->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
                'address' => $this->address,
                'pin' => $this->pin,
                'image' => $uploadedImage,
            ]);
    
            session()->flash('success', 'Organization updated successfully.');
            // if(!auth()->user()->is_medical_rep) {
            //     return redirect()->route('organization.settings.organization_settings');
            // }
    
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating organization: ' . $e->getMessage());
            return null;
        }
    }
    
    public function updatedSelectedCountry($country)
    {
        $this->states = $this->countries[$country] ?? [];
    }

    public function render()
    {
        $subscriptions = Subscription::where('is_active',true)->get();
        return view('livewire.organization.edit-org-component',compact('subscriptions'));
    }

}
