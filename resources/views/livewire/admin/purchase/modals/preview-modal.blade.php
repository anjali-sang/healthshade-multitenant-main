<!-- Preview Modal -->
<x-modal name="preview_modal" maxWidth="6xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Purchase Order: {{ $selectedPurchaseOrder->purchase_oder_number ?? '' }}
        </h3>
        <button wire:click="closePreview" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>
    </div>

    <div class="w-full h-screen">
        @if($previewUrl)
            <iframe src="{{ $previewUrl }}" class="w-full h-full border-0 rounded"></iframe>
        @endif
    </div>
</x-modal>