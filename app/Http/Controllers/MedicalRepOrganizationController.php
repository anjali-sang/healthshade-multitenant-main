<?php

namespace App\Http\Controllers;

use App\Models\MedrepOrgAccess;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRepOrganizationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $organizations = Organization::where('is_rep_org', false)
            ->where('is_active', true)
            ->get();

        $accessRecords = MedrepOrgAccess::where('medrep_id', $userId)->get();

        // Create a map of org_id => ['approved' => true/false, 'request_sent' => ..., 'rejected' => ...]
        $accessMap = $accessRecords->mapWithKeys(function ($item) {
            // If rejected and more than 1 hour has passed, reset rejection flag
            if ($item->is_rejected && $item->updated_at <= now()->subHour()) {
                $item->is_rejected = false;
                $item->request_sent = false;
                $item->save();
            }

            return [
                $item->org_id => [
                    'is_approved' => $item->is_approved,
                    'request_sent' => $item->request_sent,
                    'is_rejected' => $item->is_rejected,
                ],
            ];
        });

        return view('medical_rep.organizations', compact('organizations', 'accessMap'));
    }

    public function requestAccess($orgId)
    {
        logger()->info('Requesting access for organization ID: ' . $orgId);

        $access = MedrepOrgAccess::where('medrep_id', auth()->id())
            ->where('org_id', $orgId)
            ->first();

        if ($access) {
            // Update existing record
            $access->update([
                'request_sent' => true,
                'is_approved' => false,
                'is_rejected' => false,
            ]);
        } else {
            // Create new record
            MedrepOrgAccess::create([
                'medrep_id' => auth()->id(),
                'org_id' => $orgId,
                'request_sent' => true,
                'is_approved' => false,
                'is_rejected' => false,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }


    public function myRequests()
    {
        $userId = auth()->id();
        $orgId = auth()->user()->organization_id;

        $requests = MedrepOrgAccess::with('medicalRepresentative')
            ->where('org_id', $orgId)
            ->latest()
            ->get();

        return view('organization.medrep.request', compact('requests'));
    }

    public function approveRequest($id)
    {
        $request = MedrepOrgAccess::findOrFail($id);
        $request->update([
            'is_approved' => true,
            'is_rejected' => false,
        ]);

        return back()->with('success', 'Request approved.');
    }

    public function rejectRequest($id)
    {
        $request = MedrepOrgAccess::findOrFail($id);
        $request->update([
            'is_approved' => false,
            'is_rejected' => true,
        ]);

        return back()->with('success', 'Request rejected.');
    }

    public function viewOrganization($id)
    {
        $userId = auth()->id();

        // Check if the user has approved access to the org
        $hasAccess = MedrepOrgAccess::where('medrep_id', $userId)
            ->where('org_id', $id)
            ->where('is_approved', true)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to organization.');
        }

        // Load org with related locations
        $organization = Organization::with([
            'locations' => function ($query) {
                $query->where('is_active', true);
            }
        ])
            ->where('is_active', true)
            ->findOrFail($id);

        return view('medical_rep.organization_view', compact('organization'));
    }

}
