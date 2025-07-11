<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Livewire\Attributes\On;
use Livewire\Component;

class PurchaseOrderDetailComponent extends Component
{

    public $purchase_order;
    public $purchase_data = [];
    
    #[On('purchase-receive-view-modal')]
    public function purchaseData($rowId)
    {
        $this->purchase_order = PurchaseOrder::where('id',$rowId)->first();
        $this->purchase_data = PurchaseOrderDetail::where('purchase_order_id',$rowId)->get();
        $this->dispatch('open-modal', 'purchase_report_details_modal');
    }

    public function closeModal(){
        $this->dispatch('close-modal', 'purchase_report_details_modal');
    }
    
    public function render()
    {
        return view('livewire.purchase-order-detail-component');
    }
}