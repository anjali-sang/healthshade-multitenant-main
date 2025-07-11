<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Organization;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id == '3') {
            abort(403, 'Unauthorized.');
        }
        return view("reports.index");
    }
    public function purchaseOrderReport()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('purchase_order_report') || $user->role_id <= 2) {
            return view("reports.purchase_order_report");
        }
        return redirect()->back()->with('error', 'Not authorized access to purchase order report.');
    }
    public function PickingReport()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('picking_report') || $user->role_id <= 2) {
            return view("reports.picking_report");
        }
        return redirect()->back()->with('error', 'Not authorized access to purchase order report.');

    }
    public function AuditReport()
    {

        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('audit_report') || $user->role_id <= 2) {
            return view("reports.audit_report");
        }
        return redirect()->back()->with('error', 'Not authorized access to purchase order report.');

    }
    public function inventoryAdjust()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('inventory_adjust_report') || $user->role_id <= 2) {
            return view("reports.inventory_adjust");
        }
        return redirect()->back()->with('error', 'Not authorized access to purchase order report.');

    }

    public function inventoryTransfer()
    {

        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('inventory_transfer_report') || $user->role_id <= 2) {
            return view("reports.inventory_transfer");
        }
        return redirect()->back()->with('error', 'Not authorized access to purchase order report.');


    }

    public function productReport()
    {
        $user = auth()->user();
        $role = $user->role;

        // if ($role?->hasPermission('product_report') || $user->role_id <= 2) {
        //     $locations = [];
        //     $organizations = Organization::where('is_active', true)->get();

        //     if(auth()->user()->role_id > 1){
        //         $locations =  Location::where('id', auth()->user()->location_id)->where('is_active', true);
        //     }

        //     return view("reports.product_report", compact('organizations', 'locations'));
        // }

            $locations = [];
            $organizations = Organization::where('is_active', true)->get();
            if(auth()->user()->role_id > 1){
                $locations =  Location::where('org_id', auth()->user()->organization_id)->where('is_active', true)->get();
            }

            return view("reports.product_report", compact('organizations', 'locations'));

    }
    public function lotPickingReport(){
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('picking_report') || $user->role_id <= 2) {
            return view("reports.lot_picking_report");
        }
        return redirect()->back()->with('error', 'Not authorized access to lot picking report.');
    }
}