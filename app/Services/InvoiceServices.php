<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InvoiceServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Upload invoice file for a purchase order
     */
    public function uploadInvoice(PurchaseOrder $purchaseOrder, UploadedFile $invoiceFile): array
    {
        try {
            Log::info('Starting upload process for PO: ' . $purchaseOrder->purchase_oder_number);

            if (!$invoiceFile) {
                return [
                    'success' => false,
                    'message' => 'No invoice file selected.'
                ];
            }

            // Create directory if it doesn't exist
            if (!Storage::disk('public')->exists('invoices')) {
                Storage::disk('public')->makeDirectory('invoices');
            }

            $invoiceFileName = 'invoice_' . $purchaseOrder->purchase_oder_number . '.pdf';
            $invoicePath = $invoiceFile->storeAs('invoices', $invoiceFileName, 'public');

            Log::info('File stored at: ' . $invoicePath);

            // Update the purchase order
            $updated = $purchaseOrder->update([
                'invoice_path' => $invoicePath,
                'invoice_uploaded_at' => now(),
            ]);

            Log::info('Database updated: ' . ($updated ? 'success' : 'failed'));

            if ($updated) {
                return [
                    'success' => true,
                    'message' => 'Invoice uploaded successfully!',
                    'path' => $invoicePath
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update database.'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => 'Error uploading file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get preview URL for invoice
     */
    public function getPreviewUrl(PurchaseOrder $purchaseOrder): array
    {
        try {
            if (!$purchaseOrder->invoice_path) {
                return [
                    'success' => false,
                    'message' => 'No invoice found for this purchase order.'
                ];
            }

            // Check if file exists
            if (!Storage::disk('public')->exists($purchaseOrder->invoice_path)) {
                return [
                    'success' => false,
                    'message' => 'Invoice file not found.'
                ];
            }

            $previewUrl = asset('storage/' . $purchaseOrder->invoice_path);

            return [
                'success' => true,
                'url' => $previewUrl,
                'purchase_order' => $purchaseOrder
            ];

        } catch (\Exception $e) {
            Log::error('Preview error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error loading invoice preview.'
            ];
        }
    }

    /**
     * Download invoice file
     */


    /**
     * Delete invoice file
     */
    public function deleteInvoice(PurchaseOrder $purchaseOrder): array
    {
        try {
            if (!$purchaseOrder->invoice_path) {
                return [
                    'success' => false,
                    'message' => 'No invoice found for this purchase order.'
                ];
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($purchaseOrder->invoice_path)) {
                Storage::disk('public')->delete($purchaseOrder->invoice_path);
            }

            // Update database
            $updated = $purchaseOrder->update([
                'invoice_path' => null,
                'invoice_uploaded_at' => null,
            ]);

            if ($updated) {
                return [
                    'success' => true,
                    'message' => 'Invoice deleted successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update database.'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Delete invoice error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error deleting invoice.'
            ];
        }
    }

    /**
     * Validate uploaded file
     */
    public function validateInvoiceFile(UploadedFile $file): array
    {
        $errors = [];

        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            $errors[] = 'File size must be less than 10MB.';
        }

        // Check file type
        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'File must be PDF, JPEG, or PNG format.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Debug file information
     */
    public function debugFileInfo(UploadedFile $file = null, PurchaseOrder $purchaseOrder = null): void
    {
        Log::info('Debug Upload Called');
        Log::info('Invoice File: ' . ($file ? 'Present' : 'Not Present'));
        Log::info('Purchase Order: ' . ($purchaseOrder ? $purchaseOrder->id : 'Not Set'));

        if ($file) {
            Log::info('File Name: ' . $file->getClientOriginalName());
            Log::info('File Size: ' . $file->getSize());
            Log::info('File Mime: ' . $file->getMimeType());
        }
    }

    /**
     * Check if invoice exists for purchase order
     */
    public function hasInvoice(PurchaseOrder $purchaseOrder): bool
    {
        return !empty($purchaseOrder->invoice_path) && 
            Storage::disk('public')->exists($purchaseOrder->invoice_path);
    }

    /**
     * Get invoice file information
     */
    public function getInvoiceInfo(PurchaseOrder $purchaseOrder): array
    {
        if (!$this->hasInvoice($purchaseOrder)) {
            return [
                'exists' => false,
                'message' => 'No invoice found.'
            ];
        }

        try {
            $filePath = Storage::disk('public')->path($purchaseOrder->invoice_path);
            $fileSize = Storage::disk('public')->size($purchaseOrder->invoice_path);
            $lastModified = Storage::disk('public')->lastModified($purchaseOrder->invoice_path);

            return [
                'exists' => true,
                'path' => $purchaseOrder->invoice_path,
                'size' => $fileSize,
                'size_human' => $this->formatBytes($fileSize),
                'last_modified' => date('Y-m-d H:i:s', $lastModified),
                'uploaded_at' => $purchaseOrder->invoice_uploaded_at
            ];

        } catch (\Exception $e) {
            Log::error('Error getting invoice info: ' . $e->getMessage());
            
            return [
                'exists' => false,
                'message' => 'Error retrieving invoice information.'
            ];
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}