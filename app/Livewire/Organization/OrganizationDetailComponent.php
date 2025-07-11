<?php

namespace App\Livewire\Organization;

use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrganizationDetailComponent extends Component
{

    use WithFileUploads;
    public $currentStep = 1;
    public $totalSteps = 2;

    public $id = '';
    public $name = '';
    public $subscription_id = '1';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $state = '';
    public $city = '';
    public $country = '';
    public $pin = '';
    public $selectedCountry;
    public $states = [];

    public $logo;

    public $subscriptions = [];

    public $value = '0';


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

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'name' => 'required|unique:organizations,name|min:3|max:25',
                'email' => 'required|email',
                'phone' => 'nullable|string|max:15',
            ]);
        } elseif ($this->currentStep == 2) {
            $this->validate([
                'selectedCountry' => 'required',
                'state' => 'required',
                'address' => 'required|string|max:255',
                'pin' => 'required'
            ]);
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function mount($id = null)
    {
        if ($id) {
            $org = Organization::find($id);
            if ($org) {
                $this->id = $org->id;
                $this->name = $org->name;
                $this->email = $org->email;
                $this->subscription_id = $org->subscription_id;
                $this->selectedCountry = $org->country;
                $this->state = $org->state;
                $this->city = $org->city;
                $this->pin = $org->pin;
                $this->states = $this->countries[$this->selectedCountry] ?? [];
                $this->subscription_valid = $org->subscription_valid;
            }
        }
        $this->email = auth()->user()->email;
        $this->subscriptions = Subscription::where('is_active', true)->get();
    }

    public function updatedSelectedCountry($country)
    {
        $this->states = $this->countries[$country] ?? [];
    }
    public function createOrganization()
    {

        $this->validate([
            'name' => 'required|unique:organizations,name|min:3|max:25',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:15',
            'subscription_id' => 'required',
            'selectedCountry' => 'required',
            'state' => 'required',
            'address' => 'required|string|max:255',
            'pin' => 'required',
            'logo' => 'nullable|image|max:2048',
        ]);
        $this->country = $this->selectedCountry;
        $uploadedImage = null;
        if (!empty($this->logo)) {
            $uploadedImage  = $this->logo->store('organization_logos', 'public');
        } 

        $subs = Subscription::where('id', $this->subscription_id)->first();
        $subscription_valid_until = now()->addMonths((int) $subs->duration);
        // Create the organizations
        // $code = Organization::fetchCode();
        $org = Organization::create([
            'name' => $this->name,
            // 'organization_code' => $code+1,
            'email' => $this->email,
            'phone' => $this->phone,
            'subscription_id' => null,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'address' => $this->address,
            'pin' => $this->pin,
            'subscription_valid' => null,
            'image' => $uploadedImage,
            // 'is_rep_org' => auth()->user()->is_medical_rep ? true : false,
        ]);
        $org->organization_code = 10000 + $org->id;
        $org->save();
        $user = auth()->user();
        $user->organization_id = $org->id;
        $user->save();
        return redirect()->route('pricing');
    }
    public function selectPlan($id)
    {
        $this->subscription_id = $id;
    }
    public function render()
    {

        return view('livewire.organization.organization-detail-component');
    }
}
