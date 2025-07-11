<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class AckServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function uploadAck(PurchaseOrder $purchaseOrder, UploadedFile $ackFile): array
    {
        try {
            Log::info('Starting ACK upload process for PO: ' . $purchaseOrder->purchase_oder_number);

            if (!$ackFile) {
                return [
                    'success' => false,
                    'message' => 'No acknowledgment file selected.'
                ];
            }

            // Create directory if it doesn't exist
            if (!Storage::disk('public')->exists('acknowledgments')) {
                Storage::disk('public')->makeDirectory('acknowledgments');
            }

            $ackFileName = 'ack_' . $purchaseOrder->purchase_oder_number . '.pdf';
            $ackPath = $ackFile->storeAs('acknowledgments', $ackFileName, 'public');

            Log::info('ACK file stored at: ' . $ackPath);

            // Update the purchase order
            $updated = $purchaseOrder->update([
                'acknowledgment_path' => $ackPath,
                'acknowledgment_uploaded_at' => now(),
            ]);

            Log::info('Database updated: ' . ($updated ? 'success' : 'failed'));

            if ($updated) {
                return [
                    'success' => true,
                    'message' => 'Acknowledgment uploaded successfully!',
                    'path' => $ackPath
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update database.'
                ];
            }

        } catch (\Exception $e) {
            Log::error('ACK Upload error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => 'Error uploading file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get preview URL for acknowledgment
     */
    public function getPreviewUrl(PurchaseOrder $purchaseOrder): array
    {
        try {
            if (!$purchaseOrder->acknowledgment_path) {
                return [
                    'success' => false,
                    'message' => 'No acknowledgment found for this purchase order.'
                ];
            }

            // Check if file exists
            if (!Storage::disk('public')->exists($purchaseOrder->acknowledgment_path)) {
                return [
                    'success' => false,
                    'message' => 'Acknowledgment file not found.'
                ];
            }

            $previewUrl = asset('storage/' . $purchaseOrder->acknowledgment_path);

            return [
                'success' => true,
                'url' => $previewUrl,
                'purchase_order' => $purchaseOrder
            ];

        } catch (\Exception $e) {
            Log::error('ACK Preview error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error loading acknowledgment preview.'
            ];
        }
    }

    /**
     * Download acknowledgment file
     */
    public function downloadAck(PurchaseOrder $purchaseOrder)
    {
        try {
            if (!$purchaseOrder->acknowledgment_path) {
                return [
                    'success' => false,
                    'message' => 'No acknowledgment found for this purchase order.'
                ];
            }

            if (!Storage::disk('public')->exists($purchaseOrder->acknowledgment_path)) {
                return [
                    'success' => false,
                    'message' => 'Acknowledgment file not found.'
                ];
            }

            return Storage::disk('public')->download(
                $purchaseOrder->acknowledgment_path,
                'ack_' . $purchaseOrder->purchase_oder_number . '.pdf'
            );

        } catch (\Exception $e) {
            Log::error('ACK Download error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error downloading file.'
            ];
        }
    }

    /**
     * Delete acknowledgment file
     */
    public function deleteAck(PurchaseOrder $purchaseOrder): array
    {
        try {
            if (!$purchaseOrder->acknowledgment_path) {
                return [
                    'success' => false,
                    'message' => 'No acknowledgment found for this purchase order.'
                ];
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($purchaseOrder->acknowledgment_path)) {
                Storage::disk('public')->delete($purchaseOrder->acknowledgment_path);
            }

            // Update database
            $updated = $purchaseOrder->update([
                'acknowledgment_path' => null,
                'acknowledgment_uploaded_at' => null,
            ]);

            if ($updated) {
                return [
                    'success' => true,
                    'message' => 'Acknowledgment deleted successfully!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update database.'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Delete acknowledgment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error deleting acknowledgment.'
            ];
        }
    }

    /**
     * Validate uploaded file
     */
    public function validateAckFile(UploadedFile $file): array
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
        Log::info('Debug ACK Upload Called');
        Log::info('ACK File: ' . ($file ? 'Present' : 'Not Present'));
        Log::info('Purchase Order: ' . ($purchaseOrder ? $purchaseOrder->id : 'Not Set'));

        if ($file) {
            Log::info('File Name: ' . $file->getClientOriginalName());
            Log::info('File Size: ' . $file->getSize());
            Log::info('File Mime: ' . $file->getMimeType());
        }
    }

    /**
     * Check if acknowledgment exists for purchase order
     */
    public function hasAck(PurchaseOrder $purchaseOrder): bool
    {
        return !empty($purchaseOrder->acknowledgment_path) && 
            Storage::disk('public')->exists($purchaseOrder->acknowledgment_path);
    }

    /**
     * Get acknowledgment file information
     */
    public function getAckInfo(PurchaseOrder $purchaseOrder): array
    {
        if (!$this->hasAck($purchaseOrder)) {
            return [
                'exists' => false,
                'message' => 'No acknowledgment found.'
            ];
        }

        try {
            $filePath = Storage::disk('public')->path($purchaseOrder->acknowledgment_path);
            $fileSize = Storage::disk('public')->size($purchaseOrder->acknowledgment_path);
            $lastModified = Storage::disk('public')->lastModified($purchaseOrder->acknowledgment_path);

            return [
                'exists' => true,
                'path' => $purchaseOrder->acknowledgment_path,
                'size' => $fileSize,
                'size_human' => $this->formatBytes($fileSize),
                'last_modified' => date('Y-m-d H:i:s', $lastModified),
                'uploaded_at' => $purchaseOrder->acknowledgment_uploaded_at
            ];

        } catch (\Exception $e) {
            Log::error('Error getting acknowledgment info: ' . $e->getMessage());
            
            return [
                'exists' => false,
                'message' => 'Error retrieving acknowledgment information.'
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