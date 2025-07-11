<div class="gap-x-3 w-full">
    <div class="relative w-full mb-4">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        @include('livewire.organization.barcode.partials.print-barcode-product-search')
    </div>

    @include('livewire.organization.barcode.partials.print-barcode-product-list')

    @include('livewire.organization.barcode.modals.print-barcode-modal')
</div>

@push('scripts')
    <script>
        function printBarcodes() {
            // Get the current layout setting
            const layoutSelect = document.getElementById('barcode-layout');
            const currentLayout = layoutSelect ? layoutSelect.value : 'grid';

            const printArea = document.getElementById('barcode-print-area');
            const originalContent = document.body.innerHTML;

            let printContent = '';

            if (currentLayout === 'separte') { // Note: keeping the typo 'separte' as in your original code
                // For separate pages, wrap each barcode in a page-break container
                const barcodeItems = printArea.querySelectorAll('.border.border-gray-200');

                printContent = Array.from(barcodeItems).map(item => `
                    <div class="barcode-page">
                        <div class="barcode-item-separate">
                            ${item.innerHTML}
                        </div>
                    </div>
                `).join('');
            } else {
                // For grid and list layouts, use the existing content
                printContent = printArea.innerHTML;
            }

            document.body.innerHTML = `
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        padding: 20px;
                        margin: 0;
                    }

                    /* General print styles */
                    .barcode-wrapper {
                        page-break-inside: avoid;
                        margin-bottom: 20px;
                    }

                    /* Separate page styles */
                    .barcode-page {
                        page-break-after: always;
                        page-break-inside: avoid;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        padding: 20px;
                        box-sizing: border-box;
                    }

                    .barcode-page:last-child {
                        page-break-after: auto;
                    }

                    .barcode-item-separate {
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 30px;
                        text-align: center;
                        background: white;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        max-width: 400px;
                        width: 100%;
                    }

                    .barcode-item-separate .barcode-svg {
                        margin: 20px 0;
                    }

                    /* Grid and list layout styles */
                    .grid {
                        display: grid;
                        gap: 16px;
                    }

                    .grid-cols-1 {
                        grid-template-columns: repeat(1, minmax(0, 1fr));
                    }

                    .grid-cols-2 {
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                    }

                    .grid-cols-3 {
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                    }

                    @media print {
                        @page {
                            size: auto;
                            margin: 10mm;
                        }

                        body {
                            padding: 0;
                        }

                        /* Ensure separate pages work correctly */
                        .barcode-page {
                            page-break-after: always;
                            min-height: 90vh;
                        }

                        .barcode-page:last-child {
                            page-break-after: auto;
                        }

                        /* Hide any unwanted elements during print */
                        .no-print {
                            display: none !important;
                        }
                    }
                </style>
                <div>${printContent}</div>
            `;

            // Execute print function
            window.print();

            // Restore original content
            document.body.innerHTML = originalContent;

            // Re-initialize Alpine.js (since we replaced the body content)
            if (window.Alpine) {
                window.dispatchEvent(new CustomEvent('alpine:init'));
            }

            // Re-initialize Livewire (since we replaced the body content)
            if (window.Livewire) {
                window.Livewire.rescan();
            }
        }
    </script>
@endpush