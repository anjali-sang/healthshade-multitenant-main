<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicalRepSalesController extends Controller
{
    /**
     * Display the sales page for medical representatives.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Return the view for medical representative sales
        return view('medical_rep.sales');
    }
}
