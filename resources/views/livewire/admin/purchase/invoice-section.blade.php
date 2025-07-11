<div class="flex p-3 text-primary-dk cursor-pointer gap-3 font-semibold mt-2">
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open"
            class="flex items-center px-3 py-2 font-semibold text-primary-md hover:border-primary-md border-b-2">
            <span>Invoice</span>
        </button>

        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                @if (!$purchaseOrder->invoice_path)
                    <button wire:click="uploadInvoice({{ $purchaseOrder->id }})" @click="open = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Upload
                    </button>
                @endif
                @if ($purchaseOrder->invoice_path)
                    <button wire:click="previewInvoice({{ $purchaseOrder->id }})" @click="open = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Preview
                    </button>
                    <button wire:click="uploadInvoice({{ $purchaseOrder->id }})" @click="open = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Re-upload
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open"
            class="flex items-center px-3 py-2 font-semibold text-primary-md hover:border-primary-md border-b-2">
            <span>{{__('Order confirmation')}}</span>
        </button>

        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 z-10 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">

            <div class="py-1">
                @if (!$purchaseOrder->acknowledgment_path)
                    <button wire:click="uploadAck({{ $purchaseOrder->id }})" @click="open = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Upload
                    </button>
                @endif
                @if ($purchaseOrder->acknowledgment_path)
                    <button wire:click="previewAck({{ $purchaseOrder->id }})" @click="open = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Preview
                    </button>
                    <button wire:click="uploadAck({{ $purchaseOrder->id }})" @click="open = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Re-upload
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div class="relative">
        <button wire:click="addTrackingLink({{ $purchaseOrder->id }})"
            class="flex items-center px-3 py-2 font-semibold text-primary-md hover:border-primary-md border-b-2">
            <span>{{__('Add tracking link')}}</span>
        </button>
    </div>
</div>