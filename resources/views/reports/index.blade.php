<x-app-layout>
    <div class="max-w-5xl mx-auto px-4">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6">
            <section class="w-full">
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Reports Dashboard') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Access various reports to monitor and analyze your inventory and orders efficiently.') }}
                        </p>
                    </div>
                    <div class="relative w-full md:w-64 mt-4 md:mt-0">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchReports" placeholder="Search reports..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all">
                    </div>
                </header>
            </section>
        </div>

        <!-- Reports Grid -->
        <div id="reportsGrid" class="grid sm:grid-cols-1 lg:grid-cols-3 gap-6">
            @php
                $reports = [
                    ['route' => 'report.purchase_order', 'title' => 'Purchase Order Report', 'desc' => 'View your purchase order report with advanced filters.'],
                    ['route' => 'report.picking', 'title' => 'Picking Report', 'desc' => 'Analyze picking efficiency and order fulfillment progress.'],
                    ['route' => 'report.lot_picking', 'title' => 'Batch(LOT#) Picking Report', 'desc' => 'Analyze Batch(LOT#) picking efficiency and order fulfillment progress.'],
                    ['route' => 'report.audit', 'title' => 'Audit Report', 'desc' => 'Monitor audits and compliance status within your inventory.'],
                    ['route' => 'report.inventoryAdjust', 'title' => 'Inventory Adjust Report', 'desc' => 'Monitor all the inventory adjustments from the past.'],
                    ['route' => 'report.inventoryTransfers', 'title' => 'Inventory Transfer Report', 'desc' => 'Check all Inventory transfers done so far.'],
                    ['route' => 'report.product', 'title' => 'Product Report', 'desc' => 'Check all Products purchased quantity and amount.'],
                ];
            @endphp

            @foreach ($reports as $report)
                <div class="report-card p-6 bg-white border border-gray-200 rounded-xl shadow-md dark:bg-gray-800 dark:border-gray-700 transform transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                    data-title="{{ strtolower($report['title']) }}">
                    <a href="{{ route($report['route']) }}">
                        <h2 class="mb-2 text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                            {{ __($report['title']) }}
                        </h2>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        {{ __($report['desc']) }}
                    </p>
                    <a href="{{ route($report['route']) }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-primary-md rounded-lg hover:bg-primary-dk focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-primary-lt dark:hover:bg-primary-md dark:focus:ring-blue-800">
                        {{ __('View') }}
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- JavaScript for Filtering -->
    <script>
        document.getElementById('searchReports').addEventListener('input', function () {
            let filter = this.value.toLowerCase();
            let reportCards = document.querySelectorAll('.report-card');

            reportCards.forEach(card => {
                let title = card.getAttribute('data-title');
                if (title.includes(filter)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>