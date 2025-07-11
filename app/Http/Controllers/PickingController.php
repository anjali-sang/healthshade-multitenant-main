<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PickingController extends Controller
{
    //
    public function index(){
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('view_picking_data') || $user->role_id <= 2 ) {
            return view("organization.picking.index");
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');
    }
}
