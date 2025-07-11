<div>
    <div id="barcode-content" class="hidden">
        <div class="barcode-item mb-8 page-break-after">
            <div class="flex items-center justify-between gap-6">
                <!-- Chart Number Barcode Section -->
                <div class="flex-1 border p-4 rounded-md">
                    <div class="text-center mb-2">
                        <p class="font-bold text-lg">Chart Number: {{ $chart_number }}</p>
                    </div>
                    <div class="flex justify-center mb-3">
                        {!! $generatedBarcodes !!}
                    </div>
                    <div class="text-center mb-2">
                        <p class="font-bold text-lg">
                            {{ $biological_product?->product_code }}({{ $biological_product?->product_name }})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="biological_product_modal" width="w-100" height="h-auto" maxWidth="4xl"
        wire:model="showBiologicalModal">
        <header class="p-3 border-b border-gray-300 dark:border-gray-700">
            <h2 class="font-semibold text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Barcode Generated Successfully')}}
            </h2>
        </header>
        <div class="p-4 flex justify-between items-center">
            <!-- Results content when barcodes are generated -->
            <p class="mb-6">Your barcodes are ready. Choose an action below:</p>
            <div class="flex flex-col space-y-3">
                <button id="print-btn"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    {{__('Print Barcode')}}
                </button>

                {{-- <button id="download-all-btn"
                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download All as ZIP
                </button> --}}
            </div>
        </div>
    </x-modal>
</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Open Barcode Modal
            window.openBiologicalBarcodeModal = function (purchasedProductId) {
                Livewire.dispatch('openBiologicalBarcodeModal', { id: purchasedProductId });
            }

            // Print Functionality
            document.addEventListener('click', function (e) {
                if (e.target && (e.target.id === 'print-btn' || e.target.closest('#print-btn'))) {
                    const printContents = document.getElementById('barcode-content').innerHTML;
                    const newWin = window.open('', '_blank');

                    newWin.document.write(`
                            <html>
                            <head>
                                <title>Print Barcodes</title>
                                <style>
                                    body { 
                                        font-family: Arial, sans-serif;
                                        margin: 0;
                                        padding: 20px;
                                    }
                                    .barcode-item {
                                        margin-bottom: 30px;
                                    }
                                    .page-break-after {
                                        page-break-after: always;
                                    }
                                    .flex {
                                        display: flex;
                                    }
                                    .items-center {
                                        align-items: center;
                                    }
                                    .justify-between {
                                        justify-content: space-between;
                                    }
                                    .gap-6 {
                                        gap: 1.5rem;
                                    }
                                    .flex-1 {
                                        flex: 1;
                                    }
                                    .border {
                                        border: 1px solid #ddd;
                                    }
                                    .p-4 {
                                        padding: 1rem;
                                    }
                                    .rounded-md {
                                        border-radius: 0.375rem;
                                    }
                                    .text-center {
                                        text-align: center;
                                    }
                                    .mb-2, .mb-3 {
                                        margin-bottom: 0.5rem;
                                    }
                                    .font-bold {
                                        font-weight: bold;
                                    }
                                    .text-lg {
                                        font-size: 1.125rem;
                                    }
                                    .justify-center {
                                        justify-content: center;
                                    }
                                    svg {
                                        max-width: 100%;
                                        height: auto;
                                    }
                                    @media print {
                                        @page {
                                            size: letter;
                                            margin: 0.5cm;
                                        }
                                        .barcode-item:last-child {
                                            page-break-after: auto;
                                        }
                                    }
                                </style>
                            </head>
                            <body onload="setTimeout(function() { window.print(); window.close(); }, 500);">
                                ${printContents}
                            </body>
                            </html>
                        `);

                    newWin.document.close();
                }
            });

            // Download individual barcode as PNG
            document.addEventListener('click', function (e) {
                // Check if clicking an SVG or one of its children
                const svgElement = e.target.closest('svg');
                const barcodeItem = e.target.closest('.barcode-item');

                if (svgElement && barcodeItem && document.getElementById('barcode-content').classList.contains('hidden') === false) {
                    // Get information for filename
                    const isProductBarcode = svgElement.closest('.barcode-item > div > div:first-child') !== null;

                    let itemName;
                    if (isProductBarcode) {
                        itemName = barcodeItem.querySelector('div:first-child p:last-child').textContent.trim();
                    } else {
                        itemName = barcodeItem.querySelector('div:last-child p:last-child').textContent.trim();
                    }

                    // Process SVG to PNG
                    const svgData = new XMLSerializer().serializeToString(svgElement);
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const img = new Image();

                    img.onload = function () {
                        canvas.width = img.width * 2; // Higher resolution
                        canvas.height = img.height * 2;
                        ctx.scale(2, 2); // Scale up for better quality
                        ctx.drawImage(img, 0, 0);

                        const a = document.createElement('a');
                        a.download = `barcode-${itemName.replace(/[^a-z0-9]/gi, '-').toLowerCase()}.png`;
                        a.href = canvas.toDataURL('image/png');
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    };

                    img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
                }
            });

            // Download all barcodes as ZIP
            document.addEventListener('click', function (e) {
                if (e.target && (e.target.id === 'download-all-btn' || e.target.closest('#download-all-btn'))) {
                    // Check if JSZip is available, if not, load it
                    if (typeof JSZip === 'undefined') {
                        const script = document.createElement('script');
                        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js';
                        script.onload = processDownload;
                        document.head.appendChild(script);
                    } else {
                        processDownload();
                    }

                    function processDownload() {
                        const zip = new JSZip();
                        const barcodeItems = document.querySelectorAll('#barcode-content .barcode-item');
                        let processedCount = 0;

                        // Add loading indicator
                        const loadingDiv = document.createElement('div');
                        loadingDiv.id = 'download-loading';
                        loadingDiv.style.position = 'fixed';
                        loadingDiv.style.top = '50%';
                        loadingDiv.style.left = '50%';
                        loadingDiv.style.transform = 'translate(-50%, -50%)';
                        loadingDiv.style.background = 'rgba(0,0,0,0.7)';
                        loadingDiv.style.color = 'white';
                        loadingDiv.style.padding = '20px';
                        loadingDiv.style.borderRadius = '10px';
                        loadingDiv.innerHTML = 'Preparing barcodes... <span id="progress">0</span>/' + barcodeItems.length;
                        document.body.appendChild(loadingDiv);

                        barcodeItems.forEach((item, index) => {
                            const productName = item.querySelector('div:first-child p:last-child').textContent.trim();
                            const chartNumber = item.querySelector('div:last-child p:last-child').textContent.trim();

                            // Process both SVGs in the item
                            const svgs = item.querySelectorAll('svg');

                            svgs.forEach((svg, svgIndex) => {
                                const isProduct = svgIndex === 0;
                                const prefix = isProduct ? 'product-' : 'chart-';
                                const name = isProduct ? productName : chartNumber;

                                const svgData = new XMLSerializer().serializeToString(svg);
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');
                                const img = new Image();

                                img.onload = function () {
                                    canvas.width = img.width * 2;
                                    canvas.height = img.height * 2;
                                    ctx.scale(2, 2);
                                    ctx.drawImage(img, 0, 0);

                                    canvas.toBlob(function (blob) {
                                        const filename = `${prefix}${name.replace(/[^a-z0-9]/gi, '-').toLowerCase()}.png`;
                                        zip.file(filename, blob);

                                        // Update progress
                                        processedCount++;
                                        document.getElementById('progress').textContent = processedCount;

                                        // If all processed, generate the zip
                                        if (processedCount === barcodeItems.length * 2) {
                                            document.getElementById('download-loading').innerHTML = 'Generating zip file...';

                                            zip.generateAsync({ type: 'blob' }).then(function (content) {
                                                const a = document.createElement('a');
                                                a.download = 'biological-barcodes.zip';
                                                a.href = URL.createObjectURL(content);
                                                document.body.appendChild(a);
                                                a.click();
                                                document.body.removeChild(a);

                                                // Remove loading indicator
                                                document.body.removeChild(loadingDiv);
                                            });
                                        }
                                    });
                                };

                                img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
                            });
                        });
                    }
                }
            });

            // Event listener to show barcode content when generated
            document.addEventListener('livewire:initialize', () => {
                Livewire.on('printChartNumberBarcodes', () => {
                    document.getElementById('barcode-content').classList.remove('hidden');
                });
            });
        });
    </script>
    <script>
        Livewire.on('redirect-to-patient', () => {
            // Wait for the modal to open (optional delay if needed)
            // setTimeout(() => {
            //     window.location.href = "{{ route('patient.index') }}"; 
            // }, 1000);
        });
    </script>
@endpush