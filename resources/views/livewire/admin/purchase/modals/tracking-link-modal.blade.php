<!-- Tracking Link Modal -->
<x-modal name="tracking_link_modal" maxWidth="2xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Purchase Order: {{ $selectedPurchaseOrder->purchase_oder_number ?? '' }}
        </h3>
    </div>

    <div class="mb-4">
        <x-text-input wire:model="tracking_link" placeholder="Enter tracking link" class="w-full"></x-text-input>
    </div>
    <x-primary-button>
        <span wire:click="saveTrackingLink" class="text-white">
            Save Tracking Link
        </span>
    </x-primary-button>
</x-modal>