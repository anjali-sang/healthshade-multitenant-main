<?php

namespace App\Livewire\Organization\Barcode;

use Picqer\Barcode\BarcodeGeneratorSVG;
use Livewire\Component;

class RackBarcodeComponent extends Component
{
    public $barcodeText;
    public $generatedBarcode;
    public $showBarcode = false;

    public function generateBarcode()
    {
        $this->validate([
            'barcodeText' => 'required|string|min:1', 
        ]);

        $generator = new BarcodeGeneratorSVG();
        $this->generatedBarcode = $generator->getBarcode($this->barcodeText, $generator::TYPE_CODE_128);
        $this->showBarcode = true;
        $this->dispatch('removeSearchFocus'); 
    }

    public function updatedBarcodeText(){
        $this->generatedBarcode = '';
        $this->showBarcode = true;
    }
    
    public function render()
    {
        return view('livewire.organization.barcode.rack-barcode-component');
    }
}
