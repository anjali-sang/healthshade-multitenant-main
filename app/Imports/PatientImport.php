<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Organization;
use App\Models\Patient;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatientImport implements ToModel, WithHeadingRow
{
    public $current = 0;
    private $userId;
    private $skippedPatients = [];

    public function __construct()
    {
        $this->userId = auth()->user()->id;
    }

    public function model(array $row)
    {
        $this->current++;

        // Skip the header row
        if ($this->current == 0) {
            return null;
        }
        $chartnumber = $row['chartnumber'] ?? null;
        $address = $row['address'] ?? null;
        $city = $row['city'] ?? null;
        $state = $row['state'] ?? null;
        $country = $row['country'] ?? null;
        $ins_type = $row['ins_type'] ?? null;
        $provider = $row['provider'] ?? null;
        $account_number = $row['account_number'] ?? null;
        $drug = $row['drug'] ?? null;
        $dose = $row['dose'] ?? null;
        $frequency = $row['frequency'] ?? null;
        $location = $row['loction'] ?? null;
        $pa_expires = $row['pa_expires'] ?? null;
        $icd = $row['icd'] ?? null;
        $date_given = $row['date_given'] ?? null;
        $our_cost = !empty($row['our_cost']) ? (float) $row['our_cost'] : 0;
        $pt_copay = !empty($row['pt_copay']) ? (float) $row['pt_copay'] : 0;
        $paid = !empty($row['paid']) ? (float) $row['paid'] : 0;

        if ($chartnumber == null) {
            Log::info("Skipping patient: Chart number missing", );
            $this->skippedPatients[] = [
                'chartnumber' => $chartnumber,
                'issue' => 'Chart number not correct'
            ];
            return null;
        }

        $exits = Patient::where('chartnumber', $chartnumber)->where('organization_id', auth()->user()->organization_id)->exists();
        if ($exits) {
            Log::info("Skipping patient: Chart number exits", );
            $this->skippedPatients[] = [
                'chartnumber' => $chartnumber,
                'issue' => 'Chart number already exists'
            ];
            return null;
        }

        DB::beginTransaction();
        try {
            $patient = Patient::create([
                'chartnumber' => $chartnumber,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'organization_id' => auth()->user()->organization_id,
                'ins_type' => $ins_type,
                'provider' => $provider,
                'account_number' => $account_number,
                'drug' => $drug,
                'dose' => $dose,
                'frequency' => $frequency,
                'pa_expires' => $pa_expires,
                'location' => $location,
                'icd' => $icd,
                'date_given' => $date_given,
                'paid' => $paid,
                'our_cost' => $our_cost,
                'pt_copay' => $pt_copay,
                'profit' => $paid + $pt_copay - $our_cost,
            ]);
            DB::commit();
            return $patient;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return null;
        }

    }

    // Function to download skipped products as a CSV file
    public function downloadSkippedCsv()
    {
        if (empty($this->skippedPatients)) {
            return response()->json(['message' => 'No skipped patients to download'], 400);
        }

        $headers = ['chartnumber'];
        $csv = implode(',', $headers) . "\n";

        foreach ($this->skippedPatients as $row) {
            $csv .= implode(',', $row) . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'skipped_patients.csv');
    }

    public function getskippedPatients()
    {
        return $this->skippedPatients;
    }
}
