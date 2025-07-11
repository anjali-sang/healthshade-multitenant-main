<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UnitController extends Controller
{
    public function index(){
        if(auth()->user()->role_id != 1) {
            return redirect()->route(route: '/dashboard')->with('error', 'You do not have permission to access this page.');
        } 
        return view('admin.unit.index');
    }
}
