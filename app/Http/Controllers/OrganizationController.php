<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{

    public function create()
    {
        if (auth()->user()->organization_id) {
            return view('dashboard');
        }
        return view('organization.create');
    }
    public function adminindex()
    {
        $user = auth()->user();
        if ($user->role_id != '1') {
            return redirect('/dashboard')->with('error', 'You are not authorized to access this page');
        }
        $org = Organization::where('id', $user->organization_id)->first();
        return view('admin.organizations.index', compact('org'));
    }
    public function get_locations($organizationId)
    {
        $locations = Location::where('org_id', $organizationId)->get();
        return response()->json($locations);
    }

}
