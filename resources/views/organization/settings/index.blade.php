<x-app-layout>
    <div class="max-w-4xl mx-auto px-1 lg:px-4 py-4">
        <!-- Header Section -->
        <div class="p-2 sm:p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6">
            <section class="w-full">
                <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Settings') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Manage your organization settings, including user roles, permissions, and preferences.') }}
                        </p>
                    </div>
                    <div class="relative w-full sm:w-80 md:w-64 mt-4 sm:mt-0">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchReports" placeholder="Search settings..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all">
                    </div>
                </header>
            </section>
        </div>

        <!-- Settings List -->
        <div
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div id="settingsList" class="divide-y divide-gray-100 dark:divide-gray-700">
                @php
                    $settings = [
                        [
                            'route' => 'organization.settings.general_settings',
                            'title' => 'General Settings',
                            'desc' => 'Manage system-wide preferences including language, timezone, notifications, and display options.',
                            'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                            'category' => 'System',
                            'priority' => 'high'
                        ],
                        [
                            'route' => 'organization.settings.categories',
                            'title' => 'Categories Settings',
                            'desc' => 'Organize and manage product categories, create hierarchies, and set category-specific rules.',
                            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                            'category' => 'Products',
                            'priority' => 'medium'
                        ],

                        [
                            'route' => 'organization.settings.manufacturer',
                            'title' => 'Manufacturer Management',
                            'desc' => 'Add, edit, and organize manufacturer information, contact details, and supplier relationships.',
                            'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                            'category' => 'Products',
                            'priority' => 'medium'
                        ],
                    ];
                @endphp
                @if(!auth()->user()->is_medical_rep)
                    @php
                        if (!auth()->user()->is_medical_rep) {
                            $settings[] = [
                                'route' => 'organization.settings.organization_settings',
                                'title' => 'Organization Settings',
                                'desc' => 'Manage organizational structure, user accounts, locations, and departmental configurations.',
                                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                                'category' => 'Organization',
                                'priority' => 'high'
                            ];
                            $settings[] = [
                                'route' => 'organization.settings.inventory_transfer',
                                'title' => 'Inventory Transfer',
                                'desc' => 'Configure and monitor inventory transfers between locations, set transfer rules and approvals.',
                                'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                                'category' => 'Inventory',
                                'priority' => 'high'
                            ];

                            $settings[] = [
                                'route' => 'organization.settings.inventory_adjust',
                                'title' => 'Inventory Adjustments',
                                'desc' => 'Monitor and manage inventory adjustments, track stock changes, and maintain accurate inventory records.',
                                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0
                                                                                                                012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0
                                                                                                                01-2-2z',
                                'category' => 'Inventory',
                                'priority' => 'high'
                            ];
                            $settings[] = [
                                'route' => 'organization.settings.roles',
                                'title' => 'Roles and Permissions',
                                'desc' => 'Define user roles, set granular permissions, and manage access control across your organization.',
                                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z',
                                'category' => 'Security',
                                'priority' => 'high'
                            ];
                            $settings[] = [
                                'route' => 'pricing',
                                'title' => 'Subscription and Pricing',
                                'desc' => 'View current subscription plans, manage billing information, and upgrade or downgrade services.',
                                'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                                'category' => 'Billing',
                                'priority' => 'medium'
                            ];
                            $settings[] = [
                                'route' => 'organization.settings.customer',
                                'title' => 'Customer Management',
                                'desc' => 'Manage customer profiles, contact information, preferences, and relationship history.',
                                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                'category' => 'CRM',
                                'priority' => 'medium'
                            ];
                        }
                    @endphp

                @endif

                @foreach ($settings as $index => $setting)
                    <div class="setting-item group relative hover:bg-gradient-to-r hover:from-primary-lt/5 hover:to-primary-md/5 transition-all duration-300 cursor-pointer"
                        data-title="{{ strtolower($setting['title']) }}" data-priority="{{ $setting['priority'] }}">
                        <!-- Left border on hover -->
                        <div
                            class="absolute left-0 top-0 h-full w-1 bg-primary-md transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left">
                        </div>

                        <div class="px-4 py-4 ml-1">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-3 sm:space-y-0 flex-1 min-w-0">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-primary-lt to-primary-md rounded-lg flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all duration-300">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $setting['icon'] }}" />
                                            </svg>
                                        </div>
                                    </div>
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3
                                                class="text-base font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary-dk transition-colors duration-200">
                                                {{ __($setting['title']) }}
                                            </h3>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                                                        {{ $setting['category'] === 'System' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                                                                        {{ $setting['category'] === 'Products' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                                                                        {{ $setting['category'] === 'Inventory' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300' : '' }}
                                                                                        {{ $setting['category'] === 'Organization' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                                                                                        {{ $setting['category'] === 'Security' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}
                                                                                        {{ $setting['category'] === 'Billing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                                                                        {{ $setting['category'] === 'CRM' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300' : '' }}">
                                                    {{ $setting['category'] }}
                                                </span>
                                                {{-- @if($setting['priority'] === 'high')
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-primary-md text-white">
                                                    Essential
                                                </span>
                                                @endif --}}
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed pr-3">
                                            {{ __($setting['desc']) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="flex-shrink-0 mt-4 sm:mt-0 sm:ml-3 w-full sm:w-auto">
                                    <a href="{{ route($setting['route']) }}"
                                        class="inline-flex items-center px-4 py-2 text-xs font-semibold text-primary-dk bg-primary-lt/20 border border-primary-lt rounded-lg hover:bg-primary-md hover:text-white hover:border-primary-md focus:ring-2 focus:ring-primary-lt/50 focus:outline-none transition-all duration-200 group/btn shadow-sm hover:shadow-md">
                                        {{ __('Configure') }}
                                        <svg class="w-3 h-3 ml-1.5 rtl:rotate-180 group-hover/btn:translate-x-1 transition-transform duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="hidden">
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 mt-6 overflow-hidden">
                <div class="text-center py-12">
                    <div class="mx-auto max-w-md">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-primary-lt to-primary-md rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.034 0-3.9.785-5.291 2.09m0 0L4.5 19.5m0 0L2 22m2.5-2.5L2 17m5.291 2.09A7.962 7.962 0 0112 21a7.962 7.962 0 015.291-2.09M17 17l2.5 2.5L22 17l-2.5-2.5" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No settings found</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your search terms or browse
                            all
                            available settings above.</p>
                        <button
                            onclick="document.getElementById('searchSettings').value=''; document.getElementById('searchSettings').dispatchEvent(new Event('input'));"
                            class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-dk bg-primary-lt/20 border border-primary-lt rounded-lg hover:bg-primary-lt/30 transition-colors duration-200">
                            Clear Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript for Filtering -->
    <script>
        document.getElementById('searchSettings').addEventListener('input', function () {
            const filter = this.value.toLowerCase().trim();
            const settingItems = document.querySelectorAll('.setting-item');
            const noResults = document.getElementById('noResults');
            const settingsList = document.getElementById('settingsList').parentElement;
            let visibleCount = 0;

            settingItems.forEach(item => {
                const title = item.getAttribute('data-title');
                const description = item.querySelector('p').textContent.toLowerCase();
                const category = item.querySelector('span').textContent.toLowerCase();

                if (title.includes(filter) || description.includes(filter) || category.includes(filter)) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no results message and settings list
            if (visibleCount === 0 && filter !== '') {
                settingsList.style.display = 'none';
                noResults.classList.remove('hidden');
            } else {
                settingsList.style.display = 'block';
                noResults.classList.add('hidden');
            }
        });

        // Add keyboard navigation
        document.getElementById('searchSettings').addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('input'));
                this.blur();
            }
        });

        // Add click handler for list items
        document.querySelectorAll('.setting-item').forEach(item => {
            item.addEventListener('click', function (e) {
                if (!e.target.closest('a')) {
                    const link = this.querySelector('a');
                    if (link) {
                        window.location.href = link.href;
                    }
                }
            });
        });

        // Add smooth scroll to top on search
        document.getElementById('searchSettings').addEventListener('focus', function () {
            this.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    </script>
</x-app-layout>