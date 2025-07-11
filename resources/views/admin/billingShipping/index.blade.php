<x-app-layout>
    <div class="max-w-5xl mx-auto px-4">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6">
            <section class="w-full">
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Billing and Shipping') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Configure billing and shipping for all organizations.') }}
                        </p>
                    </div>
                    <!-- Search Bar -->
                    <div class="relative w-full md:w-64 mt-4 md:mt-0">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchSettings" placeholder="Search organization..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all">
                    </div>
                </header>
            </section>
        </div>

        <!-- Settings Grid -->
        <div id="settingsGrid" class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($org_data as $org)
                <div class="setting-card p-6 bg-white border border-gray-200 rounded-xl shadow-md dark:bg-gray-800 dark:border-gray-700 transform transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                    data-title="{{ strtolower($org['name']) }}">
                    <a href="#" class="block">
                        <h2 class="mb-2 text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                            {{ __($org['name']) }}
                        </h2>
                    </a>

                    <!-- Additional Info -->
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                        <strong>Email:</strong> {{ $org['email'] ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                        <strong>Phone:</strong> {{ $org['phone'] ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        <strong>Address:</strong> {{ $org['address'] ?? 'N/A' }}
                    </p>

                    <a href="{{ route('billing.index', ['organization_id' => $org['id']]) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary-dk to-primary-md rounded-lg shadow-md hover:from-primary-md hover:to-primary-lt focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 transition-all">
                        {{ __('View') }}
                        <svg class="rtl:rotate-180 w-4 h-4 ms-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 10">
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
        document.getElementById('searchSettings').addEventListener('input', function () {
            let filter = this.value.toLowerCase();
            let settingCards = document.querySelectorAll('.setting-card');

            settingCards.forEach(card => {
                let title = card.getAttribute('data-title');
                card.style.display = title.includes(filter) ? 'block' : 'none';
            });
        });
    </script>
</x-app-layout>
