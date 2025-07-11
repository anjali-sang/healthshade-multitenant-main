<x-modal name="print-barcode-modal" width="w-100" height="h-auto" maxWidth="4xl" wire:model="showModal">
    <header class="p-3 border-b border-gray-300 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Product Barcodes
        </h3>
        <button wire:click="$dispatch('close-modal','print-barcode-modal')"
            class="text-gray-400 hover:text-gray-500 focus:outline-none">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </header>

    <div class="p-6 overflow-y-auto" id="barcode-print-area">
        <!-- Barcode Print Settings -->
        <div class="mb-4 flex justify-between items-center">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count($barcodes) }} products selected
                </span>
            </div>
            <div class="flex space-x-4">

                <div>
                    <label for="barcode-layout" class="block text-xs text-gray-700 dark:text-gray-300 mb-1">
                        Layout
                    </label>
                    <select id="barcode-layout" wire:model.live="barcodeLayout"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                        <option value="grid">Grid</option>
                        <option value="list">List</option>
                        <option value="separte">Separate pages</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Barcode Display Area -->
        <div class="mt-4 bg-white dark:bg-gray-900">
            @if(count($barcodes) > 0)
                <div class="grid {{ $barcodeLayout === 'grid' ? 'grid-cols-2 md:grid-cols-3' : 'grid-cols-1' }} gap-4 p-4">
                    @foreach($barcodes as $item)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 text-center mx-auto">
                            <div class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $item['product_name'] }}
                            </div>
                            <div class="flex justify-center my-2 overflow-hidden">
                                <!-- SVG barcode with proper constraints -->
                                <div
                                    class="barcode-svg {{ $barcodeSize === 'small' ? 'w-32 h-16' : ($barcodeSize === 'medium' ? 'w-48 h-20' : 'w-64 h-24') }} flex items-center justify-center">
                                    <div class="max-w-full max-h-full overflow-hidden">
                                        {!! $item['barcode'] !!}
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $item['product_code'] }}
                            </div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Qty: {{ $item['quantity'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No products selected for barcode generation</p>
                </div>
            @endif
        </div>
    </div>

    <footer
        class="p-3 border-t border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
        <div class="flex space-x-3">
            {{-- <x-primary-button type="button" wire:click="downloadBarcodes" class="inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download PDF
            </x-primary-button> --}}
            <x-primary-button type="button" onclick="printBarcodes()" class="inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Barcodes
            </x-primary-button>
        </div>
    </footer>
</x-modal>