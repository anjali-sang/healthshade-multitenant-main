<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
    <!-- Barcode Generator UI -->
    <div class="col-span-12 md:col-span-6 mb-8 border-2 rounded-lg bg-white dark:bg-gray-800 p-6">
        <h1 class="text-xl font-semibold text-gray-600 dark:text-white mb-4 border-b">Rack Barcode Generator</h1>
        <div class="py-6">
            <div class="w-full mb-3">
                <label for="barcodeText" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Enter text for barcode
                </label>
                <input type="text" id="barcodeText" wire:model.live="barcodeText"
                    class="form-control w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white transition-all"
                    placeholder="Enter product ID, SKU or reference number">
            </div>
            <div class="w-full flex items-center justify-end">
                <x-primary-button wire:click="generateBarcode" wire:loading.attr="disabled">
                    <span wire:loading.remove>Generate Barcode</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                </x-primary-button>
            </div>
        </div>
    </div>

    <!-- Barcode Display Section -->
    @if ($showBarcode && $generatedBarcode)
        <div class="col-span-12 md:col-span-6 mb-8 border-2 rounded-lg bg-white dark:bg-gray-800 p-6">
            <h1 class="text-xl font-semibold text-gray-600 dark:text-white mb-4 border-b">Generated Barcode</h1>
            <div id="barcode-content"
                class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex flex-col items-center">
                    <div class="mb-1">
                        {!! $generatedBarcode !!}
                    </div>
                    <p class="text-md font-medium text-gray-700 dark:text-gray-300 mt-3">{{ $barcodeText }}</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6">
                <x-primary-button id="print-btn" class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Barcode
                </x-primary-button>

                <button id="download-btn"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md flex items-center justify-center transition-all focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download
                </button>
            </div>
        </div>
    @endif


    <script>
        document.addEventListener('livewire:initialized', function () {
            // Print Functionality
            document.addEventListener('click', function (e) {
                if (e.target && (e.target.id === 'print-btn' || e.target.closest('#print-btn'))) {
                    const printContents = document.getElementById('barcode-content').innerHTML;
                    const newWin = window.open('', '_blank');

                    newWin.document.write(`
                        <html>
                        <head>
                            <title>Print Barcode</title>
                            <style>
                                body { 
                                    font-family: Arial, sans-serif;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    height: 100vh;
                                    margin: 0;
                                    padding: 20px;
                                }
                                .barcode-container {
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    text-align: center;
                                }
                                .barcode-container svg {
                                    max-width: 100%;
                                    height: auto;
                                }
                                p {
                                    margin-top: 10px;
                                    font-size: 16px;
                                }
                                @media print {
                                    @page {
                                        size: auto;
                                        margin: 0mm;
                                    }
                                }
                            </style>
                        </head>
                        <body onload="window.print(); window.close();">
                            <div class="barcode-container">
                                ${printContents}
                            </div>
                        </body>
                        </html>
                    `);

                    newWin.document.close();
                }
            });
            document.addEventListener('click', function (e) {
                if (e.target && (e.target.id === 'download-btn' || e.target.closest('#download-btn'))) {
                    const barcodeText = document.querySelector('#barcode-content p').textContent;
                    const svgElement = document.querySelector('#barcode-content svg');

                    if (svgElement) {
                        const svgData = new XMLSerializer().serializeToString(svgElement);
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Create an image to draw to canvas
                        const img = new Image();
                        img.onload = function () {
                            canvas.width = img.width;
                            canvas.height = img.height;
                            ctx.drawImage(img, 0, 0);

                            // Convert to PNG and download
                            const a = document.createElement('a');
                            a.download = `barcode-${barcodeText}.png`;
                            a.href = canvas.toDataURL('image/png');
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        };

                        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
                    }
                }
            });
        });
    </script>
</div>