<?php

namespace App\Livewire\Organization\Patients;

use App\Models\Organization;
use App\Models\Patient;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class PatientsComponent extends Component
{
    public $notifications = [];
    public $selectedPatient = null;
    public $editMode = false;

    // Form fields
    public $patientId = null, $chartnumber = null, $address = null, $city = null, $state = null, $pin_code = null, $organization_id = null, $ins_type = null, $provider = null, $icd = null, $account_number = null, $drug = null, $dose = null, $frequency = null, $location = null, $pa_expires = null, $date_given = null;
    public $paid = 0;
    public $pt_copay = 0;
    public $our_cost = 0;
    public $profit = 0;
    public $organization;
    // Countries and states data
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
    public $selectedCountry = null;
    public $states = [];
    public function updated($field)
    {
        if (in_array($field, ['paid', 'our_cost', 'pt_copay'])) {
            $this->calculateProfit();
        }
    }

    public function calculateProfit()
    {
        $paid = is_numeric($this->paid) ? (float) $this->paid : 0;
        $pt_copay = is_numeric($this->pt_copay) ? (float) $this->pt_copay : 0;
        $our_cost = is_numeric($this->our_cost) ? (float) $this->our_cost : 0;

        $this->profit = round($paid + $pt_copay - $our_cost, 2);
    }
    public function mount()
    {
        $this->organization = auth()->user()->organization;
        $this->calculateProfit();
    }

    public function updatedSelectedCountry($country)
    {
        $this->states = $this->countries[$country] ?? [];
        // Reset state when country changes
        $this->state = null;
    }

    public function createPatient()
    {
        $this->validate([
            'chartnumber' => 'required',
            'selectedCountry' => 'required',
        ]);

        // Create the patient
        Patient::create([
            'chartnumber' => $this->chartnumber,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->selectedCountry,
            'pin_code' => $this->pin_code,
            'organization_id' => auth()->user()->organization_id,
            'ins_type' => $this->ins_type,
            'provider' => $this->provider,
            'icd' => $this->icd,
            'drug' => $this->drug,
            'dose' => $this->dose,
            'frequency' => $this->frequency,
            'location' => $this->location,
            'pa_expires' => $this->pa_expires,
            'date_given' => $this->date_given,
            'paid' => $this->paid,
            'our_cost' => $this->our_cost,
            'pt_copay' => $this->pt_copay,
            'profit' => $this->profit
        ]);

        $this->addNotification('Patient created successfully!');
        $this->resetForm();
        $this->dispatch('close-modal', 'patient-modal');
        $this->dispatch('pg:eventRefresh-patients-list-gx9aih-table');
    }

    public function updatePatient()
    {
        $this->validate([
            'chartnumber' => 'required',
            'selectedCountry'=>'required',
            'state'=>'required',
            'pin_code'=>'required'
        ]);

        $patient = Patient::find($this->patientId);

        if (!$patient) {
            $this->addNotification('Patient not found!', 'error');
            return;
        }

        $patient->update([
            'chartnumber' => $this->chartnumber,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->selectedCountry,
            'pin_code' => $this->pin_code,
            'ins_type' => $this->ins_type,
            'provider' => $this->provider,
            'icd' => $this->icd,
            'drug' => $this->drug,
            'dose' => $this->dose,
            'frequency' => $this->frequency,
            'location' => $this->location,
            'pa_expires' => $this->pa_expires,
            'date_given' => \Carbon\Carbon::parse($this->date_given)->format(
                session('date_format', 'Y-m-d')),
            'paid' => $this->paid,
            'our_cost' => $this->our_cost,
            'pt_copay' => $this->pt_copay,
            'profit' => $this->profit
        ]);

        $this->addNotification('Patient updated successfully!');
        $this->resetForm();
        $this->dispatch('close-modal', 'patient-modal');
        $this->dispatch('pg:eventRefresh-patients-list-gx9aih-table');
    }

    #[On('editPatient')]
    public function editPatient($patientId)
    {
        $this->editMode = true;
        $this->patientId = $patientId;
        $patient = Patient::find($patientId);

        if (!$patient) {
            $this->addNotification('Patient not found!', 'error');
            return;
        }

        // Fill form with patient data
        $this->chartnumber = $patient->chartnumber;
        $this->selectedCountry = $patient->country;
        // Load states for the selected country
        $this->updatedSelectedCountry($patient->country);
        $this->state = $patient->state;
        $this->address = $patient->address;
        $this->city = $patient->city;
        $this->pin_code = $patient->pin_code;
        $this->ins_type = $patient->ins_type;
        $this->provider = $patient->provider;
        $this->icd = $patient->icd;
        $this->drug = $patient->drug;
        $this->dose = $patient->dose;
        $this->frequency = $patient->frequency;
        $this->location = $patient->location;
        $this->pa_expires = $patient->pa_expires;
        $this->date_given = Carbon::parse($patient->date_given)->format(session('date_format', 'm-d-Y'));
        $this->paid = $patient->paid;
        $this->our_cose = $patient->our_cost;
        $this->pt_copay = $patient->pt_copay;
        $this->profit = $patient->profit;
        logger( $this->date_given);

        // Open the modal
        $this->dispatch('open-modal', 'patient-modal');
    }
    #[On('deletePatient')]
    public function deletePatient($patientId)
    {
        $this->patientId = $patientId;
        $patient = Patient::find($patientId);
        $this->chartnumber = $patient->chartnumber;
        if (!$patient) {
            $this->addNotification('Patient not found!', 'error');
            return;
        }
        $this->dispatch('open-modal', 'delete-patient-modal');
    }
    public function confirmdeletePatient()
    {
        $patient = Patient::find($this->patientId);

        if (!$patient) {
            $this->addNotification('Patient not found!', 'error');
            return;
        }
        $patient->is_active = false;
        $patient->save();

        $this->addNotification('Patient data deleted successfully!');
        $this->dispatch('close-modal', 'delete-patient-modal');
        $this->dispatch('pg:eventRefresh-patients-list-gx9aih-table');
    }

    public function addPatient()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->dispatch('open-modal', 'patient-modal');
    }

    public function resetForm()
    {
        $this->reset([
            'patientId',
            'chartnumber',
            'address',
            'city',
            'state',
            'selectedCountry',
            'pin_code',
            'states',
            'editMode',
            'ins_type',
            'provider',
            'icd',
            'account_number',
            'drug',
            'dose',
            'frequency',
            'location',
            'pa_expires'
        ]);
    }

    public function addNotification($message, $type = 'success')
    {
        // Prepend new notifications to the top of the array
        array_unshift($this->notifications, [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ]);

        // Limit to a maximum of 5 notifications
        $this->notifications = array_slice($this->notifications, 0, 5);
    }

    public function removeNotification($id)
    {
        $this->notifications = array_values(array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        }));
    }

    public function downloadSampleCsv()
    {
        $headers = [
            'chartnumber',
            'city',
            'state',
            'address',
            'country',
            'zip',
            'ins_type',
            'provider',
            'icd',
            'drug',
            'dose',
            'frequency',
            'date_given',
            'our_cost',
            'pt_copay',
            'paid',
        ];

        $sampleData = [
            [
                'chartnumber' => '123456',
                'city' => 'New York',
                'state' => 'NY',
                'address' => '123 Main St',
                'country' => 'USA',
                'zip' => '10001',
                'ins_type' => 'Insurance Type',
                'provider' => 'Provider Name',
                'icd' => 'ICD Code',
                'drug' => 'Drug Name',
                'dose' => '50mg',
                'frequency' => 'Once a day',
                'date_given' => '2024-12-31',
                'our_cost' => 10,
                'pt_copay' => 2,
                'paid' => 13
            ],
        ];
        $csv = implode(',', $headers) . "\n";
        foreach ($sampleData as $row) {
            $csv .= implode(',', $row) . "\n";
        }
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'sample_patients_import.csv');
    }

    public function render()
    {
        return view('livewire.organization.patients.patients-component');
    }
}