<?php

namespace App\Http\Controllers;

use App\Models\MedicalRepSales;
use App\Models\Shipment;
use App\Services\UPSShippingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UpsShippingController extends Controller
{
    protected $upsService;

    public function __construct(UPSShippingService $upsService)
    {
        $this->upsService = $upsService;
    }
    /**
     * Create UPS shipment for a sale
     */
    public function createShipment(Request $request): JsonResponse
    {
        $request->validate([
            'sale_id' => 'required|exists:medical_rep_sales,id'
        ]);

        try {
            $saleId = $request->input('sale_id');

            // Check if shipment already exists for this sale
            $existingShipment = Shipment::where('sale_id', $saleId)->first();
            if ($existingShipment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipment already exists for this sale',
                    'shipment' => $existingShipment
                ], 400);
            }

            $result = $this->upsService->createShipment($saleId);

            if ($result['success']) {
                // Update sale status
                $sale = MedicalRepSales::find($saleId);
                $sale->update(['status' => 'shipped']);

                return response()->json([
                    'success' => true,
                    'message' => 'Shipment created successfully',
                    'shipment' => $result['shipment'],
                    'tracking_number' => $result['tracking_number'],
                    'label_url' => $result['label_url']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);

        } catch (\Exception $e) {
            Log::error('Shipment creation failed', [
                'sale_id' => $request->input('sale_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the shipment'
            ], 500);
        }
    }

    /**
     * Get shipment details
     */
    public function getShipment($saleId): JsonResponse
    {
        try {
            $shipment = Shipment::where('sale_id', $saleId)
                ->with('sale')
                ->first();

            if (!$shipment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'shipment' => $shipment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving shipment details'
            ], 500);
        }
    }

    /**
     * Track shipment
     */
    public function trackShipment(Request $request): JsonResponse
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);

        try {
            $trackingNumber = $request->input('tracking_number');
            $result = $this->upsService->trackShipment($trackingNumber);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error tracking shipment'
            ], 500);
        }
    }

    /**
     * Get all shipments
     */
    public function getAllShipments(Request $request): JsonResponse
    {
        try {
            $query = Shipment::with(['sale.organization', 'sale.receiverOrganization']);

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filter by date range if provided
            if ($request->has('from_date')) {
                $query->whereDate('created_at', '>=', $request->input('from_date'));
            }

            if ($request->has('to_date')) {
                $query->whereDate('created_at', '<=', $request->input('to_date'));
            }

            $shipments = $query->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 15));

            return response()->json([
                'success' => true,
                'shipments' => $shipments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving shipments'
            ], 500);
        }
    }

    /**
     * Download shipping label
     */
    public function downloadLabel($shipmentId)
    {
        try {
            $shipment = Shipment::findOrFail($shipmentId);

            if (!$shipment->label_url) {
                return response()->json([
                    'success' => false,
                    'message' => 'Label not available'
                ], 404);
            }

            $labelPath = storage_path('app/public' . str_replace('/storage', '', $shipment->label_url));

            if (!file_exists($labelPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Label file not found'
                ], 404);
            }

            return response()->download($labelPath, 'shipping_label_' . $shipment->tracking_number . '.gif');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error downloading label'
            ], 500);
        }
    }

}