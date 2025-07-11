<?php

namespace App\Livewire\Admin\Purchase;

use App\Models\Organization;
use App\Models\PurchaseOrder;
use App\Services\AckServices;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\InvoiceServices;

class PurchaseComponent extends Component
{
    use WithFileUploads;

    public $user = '';
    public $viewPurchaseOrder = false;
    public $showModal = false;
    public $purchaseOrder = null;
    public $invoiceFile;
    public $ackFile;
    public $showPreviewModal = false;
    public $previewUrl = null;
    public $selectedPurchaseOrder = null; // Add this missing property

    public $tracking_link;
    protected InvoiceServices $invoiceService;
    protected AckServices $ackServices; // Fix property name

    public function boot(InvoiceServices $invoiceService, AckServices $ackServices)
    {
        $this->invoiceService = $invoiceService;
        $this->ackServices = $ackServices; // Fix property assignment
    }

    protected $rules = [
        'invoiceFile' => 'required|file|mimes:pdf|max:10240',
        'ackFile' => 'required|file|mimes:pdf|max:10240',
    ];

    protected $messages = [
        'invoiceFile.required' => 'Invoice PDF is required.',
        'invoiceFile.mimes' => 'Invoice must be a PDF file.',
        'invoiceFile.max' => 'Invoice file size must not exceed 10MB.',
        'ackFile.required' => 'Acknowledgment PDF is required.', // Fix message
        'ackFile.mimes' => 'Acknowledgment must be a PDF file.', // Fix message
        'ackFile.max' => 'Acknowledgment file size must not exceed 10MB.', // Fix message
    ];

    #[On('rowClicked')]
    public function fetchPoModal($id)
    {
        $this->viewPurchaseOrder = true;
        $this->purchaseOrder = PurchaseOrder::with(['purchasedProducts.product', 'purchasedProducts.unit', 'purchaseLocation', 'organization'])
            ->where('purchase_orders.id', $id)
            ->leftJoin('bill_to_locations', function ($join) {
                $join->on('purchase_orders.bill_to_location_id', '=', 'bill_to_locations.location_id')
                    ->on('purchase_orders.supplier_id', '=', 'bill_to_locations.supplier_id');
            })
            ->select('purchase_orders.*', 'bill_to_locations.bill_to')
            ->first();
    }

    public function uploadInvoice($id)
    {
        $this->purchaseOrder = PurchaseOrder::find($id);
        $this->dispatch('open-modal', 'upload_Invoice_model');
        $this->reset(['invoiceFile', 'ackFile']);
        $this->resetValidation();
    }

    public function uploadAck($id)
    {
        $this->purchaseOrder = PurchaseOrder::find($id);
        $this->dispatch('open-modal', 'upload_ack_modal');
        $this->reset(['invoiceFile', 'ackFile']);
        $this->resetValidation();
    }

    public function submitUploadInvoice()
    {
        // Validate only invoice file
        $this->validateOnly('invoiceFile');

        $result = $this->invoiceService->uploadInvoice($this->purchaseOrder, $this->invoiceFile);

        if ($result['success']) {
            $this->reset(['invoiceFile', 'ackFile']);
            $this->dispatch('close-modal', 'upload_Invoice_model');
            session()->flash('message', $result['message']);
            // Add this if you have a method to refresh data
            // $this->fetchPurchaseData();
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function submitUploadAck()
    {
        // Validate only ack file
        $this->validateOnly('ackFile');

        $result = $this->ackServices->uploadAck($this->purchaseOrder, $this->ackFile);

        if ($result['success']) {
            $this->reset(['invoiceFile', 'ackFile']);
            $this->dispatch('close-modal', 'upload_ack_modal');
            session()->flash('message', $result['message']);
            // Add this if you have a method to refresh data
            // $this->fetchPurchaseData();
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['invoiceFile', 'ackFile']);
        $this->resetValidation();
        $this->dispatch('close-modal', 'upload_Invoice_model');
        $this->dispatch('close-modal', 'upload_ack_modal'); // Fix modal name
    }

    public function debugUpload()
    {
        $this->invoiceService->debugFileInfo($this->invoiceFile, $this->purchaseOrder);
        $this->ackServices->debugFileInfo($this->ackFile, $this->purchaseOrder);
    }

    public function removeInvoiceFile()
    {
        $this->invoiceFile = null;
        $this->resetValidation(['invoiceFile']);
    }

    public function removeAckFile()
    {
        $this->ackFile = null; // Fix: was setting invoiceFile instead of ackFile
        $this->resetValidation(['ackFile']); // Fix: was resetting invoiceFile validation
    }

    public function updatedInvoiceFile()
    {
        $this->validateOnly('invoiceFile');

        // Optional: Validate using service
        if ($this->invoiceFile) {
            $validation = $this->invoiceService->validateInvoiceFile($this->invoiceFile);
            if (!$validation['valid']) {
                $this->addError('invoiceFile', implode(' ', $validation['errors']));
            }
        }
    }

    public function updatedAckFile()
    {
        $this->validateOnly('ackFile');

        // Optional: Validate using service
        if ($this->ackFile) {
            $validation = $this->ackServices->validateAckFile($this->ackFile);
            if (!$validation['valid']) {
                $this->addError('ackFile', implode(' ', $validation['errors']));
            }
        }
    }

    public function previewInvoice($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->invoiceService->getPreviewUrl($purchaseOrder);

        if ($result['success']) {
            $this->selectedPurchaseOrder = $result['purchase_order'];
            $this->previewUrl = $result['url'];
            $this->dispatch('open-modal', 'preview_modal');
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function previewAck($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->ackServices->getPreviewUrl($purchaseOrder);

        if ($result['success']) {
            $this->selectedPurchaseOrder = $result['purchase_order'];
            $this->previewUrl = $result['url'];
            $this->dispatch('open-modal', 'preview_modal');
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function closePreview()
    {
        $this->dispatch('close-modal', 'preview_modal');
        $this->selectedPurchaseOrder = null;
        $this->previewUrl = null;
    }

    public function downloadInvoice($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->invoiceService->downloadInvoice($purchaseOrder);

        if (is_array($result) && !$result['success']) {
            session()->flash('error', $result['message']);
        } else {
            return $result; // This will be the download response
        }
    }

    public function downloadAck($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->ackServices->downloadAck($purchaseOrder);

        if (is_array($result) && !$result['success']) {
            session()->flash('error', $result['message']);
        } else {
            return $result; // This will be the download response
        }
    }

    public function deleteInvoice($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->invoiceService->deleteInvoice($purchaseOrder);

        if ($result['success']) {
            session()->flash('message', $result['message']);
            // Add this if you have a method to refresh data
            // $this->fetchPurchaseData();
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function deleteAck($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $result = $this->ackServices->deleteAck($purchaseOrder);

        if ($result['success']) {
            session()->flash('message', $result['message']);
            // Add this if you have a method to refresh data
            // $this->fetchPurchaseData();
        } else {
            session()->flash('error', $result['message']);
        }
    }

    // Helper methods
    public function hasInvoice($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        return $this->invoiceService->hasInvoice($purchaseOrder);
    }

    public function hasAck($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        return $this->ackServices->hasAck($purchaseOrder);
    }

    public function addTrackingLink($purchaseOrderId)
    {
        $this->selectedPurchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);
        $this->tracking_link = $this->selectedPurchaseOrder->tracking_link ?? '';
        $this->dispatch('open-modal', 'tracking_link_modal');
    }

    public function saveTrackingLink()
    {
        $this->validate([
            'tracking_link' => 'required|url',
        ]);
        $purchaseOrder = PurchaseOrder::findOrFail($this->selectedPurchaseOrder->id);
        $purchaseOrder->tracking_link = $this->tracking_link;
        $purchaseOrder->note = "Your order is on the way.";
        $purchaseOrder->save();
        $this->dispatch('close-modal', 'tracking_link_modal');
    }

    public function render()
    {
        return view('livewire.admin.purchase.purchase-component');
    }
}