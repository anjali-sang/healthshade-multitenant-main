<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class SettingController extends Controller
{
    public function settings()
    {
        return view('organization.settings.index');
    }
    public function categories()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('categories_settings') || $user->role_id <= 2) {
            return view('organization.settings.category_settings.index');
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');

    }
    public function inventory_adjustment()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('inventory_adjustments') || $user->role_id <= 2) {
            return view('organization.settings.inventory_adjustments.index');
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');

    }

    public function inventory_transfer()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('inventory_transfers') || $user->role_id <= 2) {
            return view('organization.settings.inventory_transfers.index');
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');

    }

    public function organization_settings()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('view_organization_data') || $user->role_id <= 2) {
            $org = Organization::find($user->organization_id);

            return view('organization.settings.organization_settings.index', compact('org'));
        }

        // Abort with unauthorized action message if the permission check fails
        return redirect()->back()->with('error', 'You do not have permission to view this page.');

    }

    public function general_settings()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('general_settings') || $user->role_id <= 2) {
            $organization = Organization::where('id', auth()->user()->organization_id)->first();
            return view('organization.settings.general_settings.index', compact('organization'));
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');

    }

    public function general_settings_update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'currency' => 'sometimes|string|max:3',
            'timezone' => 'sometimes|string|max:50',
            'date_format' => 'sometimes|string',
            'time_format' => 'sometimes|string',
        ]);

        $organization = Organization::where('id', auth()->user()->organization_id)->first();
        $organization->update($validated);
        $user = auth()->user();
        if ($user->organization) {
            session([
                'currency' => $user->organization->currency,
                'timezone' => $user->organization->timezone,
                'date_format' => $user->organization->date_format,
                'time_format' => $user->organization->time_format,
            ]);
        }
        return redirect()->back()->with('status', 'Organization updated successfully');
    }
    public function manufacturer()
    {
        $user = auth()->user();
        $role = $user->role;
        // Check permission or specific role ID
        if ($role?->hasPermission('manufacturer_settings') || $user->role_id <= 2) {
            return view('organization.settings.manufacturer.index');
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');
    }

    public function roles()
    {
        $user = auth()->user();
        $role = $user->role;
        // Check permission or specific role ID
        if ($role?->hasPermission('roles_settings') || $user->role_id <= 2) {
            return view('organization.settings.roles.index');
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');

    }
    public function customer_settings()
    {
        return view('organization.settings.customer.index');
    }
}