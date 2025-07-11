<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        // Check permission or specific role ID
        if ($role?->hasPermission('view_purchase_data') || $user->role_id <= 2) {
            return view('organization.purchase.index');
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');
    }

    public function adminPurchase()
    {
        $user = auth()->user();
        if ($user->role_id == 1) {
            return view('admin.purchase.index');
        }
        return redirect()->back()->with('error', 'You do not have permission to view this page.');
    }
    public function downloadInvoice($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if (!$purchaseOrder->hasInvoice()) {
            abort(404, 'Invoice file not found.');
        }

        $filePath = storage_path('app/public/' . $purchaseOrder->invoice_path);
        $fileName = 'Invoice_' . $purchaseOrder->purchase_oder_number . '.pdf';

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->download($filePath, $fileName);
    }

    public function downloadAcknowledgment($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $filePath = storage_path('app/public/' . $purchaseOrder->acknowledgment_path);
        $fileName = 'Acknowledgment_' . $purchaseOrder->purchase_oder_number . '.pdf';

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->download($filePath, $fileName);
    }

    public function previewInvoice($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if (!$purchaseOrder->hasInvoice()) {
            abort(404, 'Invoice file not found.');
        }

        $filePath = storage_path('app/public/' . $purchaseOrder->invoice_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice_' . $purchaseOrder->purchase_oder_number . '.pdf"'
        ]);
    }

    public function previewAcknowledgment($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if (!$purchaseOrder->hasAcknowledgment()) {
            abort(404, 'Acknowledgment file not found.');
        }

        $filePath = storage_path('app/public/' . $purchaseOrder->acknowledgment_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Acknowledgment_' . $purchaseOrder->purchase_oder_number . '.pdf"'
        ]);
    }

    public function reorder($id)
    {
        $user = auth()->user();
        $role = $user->role;
        $originalOrder = PurchaseOrder::with('purchasedProducts')->findOrFail($id);
        // Case 1: User has 'approve_all_cart' permission OR is an admin-level role (role_id <= 2)
        $hasApproveAllPermission = $role?->hasPermission('approve_all_cart') || $user->role_id <= 2;
        // Case 2: User has 'approve_own_cart' permission AND is at the matching location
        $hasApproveOwnPermission = $role?->hasPermission('approve_own_cart') && $user->location_id == $originalOrder->location_id;
        // If neither permission condition is met, deny access
        if (!$hasApproveAllPermission && !$hasApproveOwnPermission) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to create a purchase order.']);
        }
        $poNumber = PurchaseOrder::generatePurchaseOrderNumber();

        $newOrder = $originalOrder->replicate();
        $newOrder->status = 'ordered';
        $newOrder->note = 'Order is being processed and will be placed shortly';
        $newOrder->is_order_placed = false;
        $newOrder->purchase_oder_number = $poNumber;
        $newOrder->created_at = now();
        $newOrder->save();
        foreach ($originalOrder->purchasedProducts as $product) {
            $clonedProduct = $product->replicate();
            $clonedProduct->received_quantity = 0;
            $newOrder->purchasedProducts()->create($clonedProduct->toArray());
        }
        return response()->json(['success' => true, 'new_order_id' => $newOrder->id]);
    }

}
