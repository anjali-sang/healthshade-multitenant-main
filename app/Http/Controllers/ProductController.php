<?php

namespace App\Http\Controllers;

use App\Imports\CatalogImport;
use App\Models\Mycatalog;
use App\Models\Organization;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use Excel;

class ProductController extends Controller
{
    public function adminProducts()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        if (auth()->user()->role_id != '1') {
            return redirect('/dashboard')->with('error', 'You are not authorized to access this module.');
        }
        return view('admin.products.index', compact('suppliers'));
    }

    public function OrganizationCatalog()
    {

        $catalog = MyCatalog::where('mycatalogs.organization_id', auth()->user()->organization_id)
            ->leftJoin('products', 'mycatalogs.product_id', '=', 'products.id')
            ->where('products.is_active', true)
            ->count();
        
        $user = auth()->user();
        $role = $user->role;
        // Check permission or specific role ID
        if ($role?->hasPermission('view_products_data') || $user->role_id <= 2) {
            $org = Organization::find($user->organization_id);
            return view('organization.catalog.index', compact('catalog'));
        }   
        return redirect()->back()->with('error', 'You do not have permission to view this page.');
    }

    public function OrganizationInventory()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('view_inventory_data') || $user->role_id <= 2 ) {
            return view('organization.inventory.index');
        }
        // Abort with unauthorized action message if the permission check fails
        return redirect()->back()->with('error', 'You do not have permission to view this page.');
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
            'supplier_id' => 'required|string|max:255',
        ]);

        $import = new ProductImport($request->input('supplier_id'));
        Excel::import($import, $request->file('csv_file'));
        if (!empty($import->getskippedProducts())) {
            return $import->downloadSkippedCsv();
        }

        return back();
    }
    public function importCatalog(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $import = new CatalogImport();
        Excel::import($import, $request->file('csv_file'));
        if (!empty($import->getskippedProducts())) {
            return $import->downloadSkippedCsv();
        }

        return back();
    }

}