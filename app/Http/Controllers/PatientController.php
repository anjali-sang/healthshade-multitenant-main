<?php

namespace App\Http\Controllers;

use App\Imports\PatientImport;
use Illuminate\Http\Request;
use Excel;

class PatientController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('view_patient') || $user->role_id <= 2 ) {
            return view("organization.patients.index");
        }
        return redirect('/dashboard')->with('error', 'You do not have permission to view this page.');
    }

    public function importPatients(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $import = new PatientImport();
        Excel::import($import, $request->file('csv_file'));
        if (!empty($import->getskippedpatients())) {
            return $import->downloadSkippedCsv();
        }

        return back();
    }
    

}
