<x-app-layout>
    <div class="p-5">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <!-- Tab Navigation -->
            <div class="dark:border-gray-700 px-6 py-4">
                <nav class="flex space-x-8 py-3" role="tablist">
                    <button id="print-barcode-btn" data-section="print-barcode" role="tab" aria-controls="print-barcode-section" aria-selected="true"
                        class="tab-btn flex items-center justify-center pb-3 -mb-px text-primary-md font-medium border-b-2 border-primary-md hover:border-primary-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 8h10M9 12h10M9 16h10M5 8H5m0 4h.01m0 4H5" />
                        </svg>
                        {{ __('Print Barcode') }}
                    </button>
                    <button id="rack-barcode-btn" data-section="rack-barcode" role="tab" aria-controls="rack-barcode-section" aria-selected="false"
                        class="tab-btn flex items-center justify-center pb-3 -mb-px text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:border-primary-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                        </svg>
                        {{ __('Rack Barcode') }}
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <section id="print-barcode-section" class="tab-content p-6" role="tabpanel">
                <livewire:organization.barcode.print-barcode-component />
            </section>
            <section id="rack-barcode-section" class="tab-content hidden p-6" role="tabpanel">
                <livewire:organization.barcode.rack-barcode-component />
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.tab-btn');
            const sections = {
                'print-barcode': document.getElementById('print-barcode-section'),
                'rack-barcode': document.getElementById('rack-barcode-section')
            };

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const target = button.dataset.section;

                    // Show the selected section and hide others
                    Object.entries(sections).forEach(([key, section]) => {
                        if (key === target) {
                            section.classList.remove('hidden');
                        } else {
                            section.classList.add('hidden');
                        }
                    });

                    // Update tab styles
                    buttons.forEach(btn => {
                        const isActive = btn.dataset.section === target;

                        btn.classList.toggle('text-primary-md', isActive);
                        btn.classList.toggle('border-primary-md', isActive);

                        btn.classList.toggle('text-gray-500', !isActive);
                        btn.classList.toggle('border-transparent', !isActive);

                        btn.setAttribute('aria-selected', isActive);
                    });
                });
            });

            // Auto-click the first tab on load
            document.getElementById('print-barcode-btn').click();
        });
    </script>

    <style>
        .tab-btn {
            transition: all 0.2s ease-in-out;
        }

        .tab-content {
            transition: opacity 0.2s ease-in-out;
        }

        button:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
    </style>
</x-app-layout>
