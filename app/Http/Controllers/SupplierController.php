<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index() {
        if (!auth()->check() || auth()->user()->role_id != '1') {
            return redirect()->back()->with('error', 'You are not authorized to access this module.');
        }
        return view('admin.supplier.index');
    }
    
   public function getSupplierCosts()
{
    try {
        $orgId = auth()->user()->organization_id;

        $suppliers = DB::table('suppliers')
            ->leftJoin('purchase_orders', 'suppliers.id', '=', 'purchase_orders.supplier_id')
            ->select(
                'suppliers.id',
                'suppliers.supplier_name',
                DB::raw("
                    COALESCE(SUM(
                        CASE 
                            WHEN purchase_orders.organization_id = {$orgId}
                            THEN purchase_orders.total
                            ELSE 0
                        END
                    ), 0) as total_cost
                ")
            )
            ->where('suppliers.is_active', 1)
            ->groupBy('suppliers.id', 'suppliers.supplier_name')
            ->get();

        return response()->json($suppliers);
    } catch (\Exception $e) {
       
        return response()->json(['error' => 'Internal server error'], 500);
    }
}


}

 
