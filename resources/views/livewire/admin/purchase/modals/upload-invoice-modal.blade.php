<x-modal name="upload_Invoice_model" width="w-100" height="h-auto" maxWidth="6xl">
    <header class="p-6 border-b border-gray-300 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
                Upload Invoice - {{ $purchaseOrder->purchase_oder_number ?? 'N/A' }}
            </h2>
            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </header>

    <div class="bg-gray-50 dark:bg-gray-800">
        <div class="p-6 space-y-6">

            <!-- Debug Info (remove after fixing) -->
            <div class="bg-yellow-50 border border-yellow-200 rounded p-4 flex justify-between items-center">
                <div class="text-sm text-yellow-700">
                    <p>Purchase Order ID: {{ $purchaseOrder->purchase_oder_number ?? 'Not Set' }}</p>
                    <p>Invoice File:
                        {{ $invoiceFile ? 'Selected (' . $invoiceFile->getClientOriginalName() . ')' : 'Not Selected' }}
                    </p>
                </div>
                <!-- Upload Progress -->
                <div wire:loading wire:target="invoiceFile" class="text-center">
                    <div
                        class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-yellow-700">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Processing...
                    </div>
                </div>
            </div>

            <!-- Invoice Upload Section -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Invoice PDF <span class="text-red-500">*</span>
                    </label>

                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            @if ($invoiceFile)
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $invoiceFile->getClientOriginalName() }}</span>
                                    <button type="button" wire:click="removeInvoiceFile"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="invoice-upload"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload invoice PDF</span>
                                        <input id="invoice-upload" wire:model.live="invoiceFile" type="file" accept=".pdf"
                                            class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF up to 10MB</p>
                            @endif
                        </div>
                    </div>

                    @error('invoiceFile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-end space-x-3">
            <button type="button" wire:click="closeModal"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-md transition-colors duration-200">
                Cancel
            </button>

            <button wire:click="submitUploadInvoice" wire:loading.attr="disabled" wire:target="submitUploadInvoice"
                @disabled(!$invoiceFile)
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-medium rounded-md transition-colors duration-200 flex items-center space-x-2">
                <span wire:loading.remove wire:target="submitUploadInvoice">Upload Invoice</span>
                <span wire:loading wire:target="submitUploadInvoice" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Uploading...
                </span>
            </button>
        </div>
    </div>
</x-modal>
