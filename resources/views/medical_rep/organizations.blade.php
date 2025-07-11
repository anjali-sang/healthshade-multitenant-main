{{-- <x-app-layout>
    <div class="max-w-7xl mx-auto p-4 space-y-4">
        <!-- Header -->
        <div class="bg-white/90 backdrop-blur rounded-xl p-4 py-5 shadow-lg border border-white/20">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h1
                        class="text-2xl font-bold bg-gradient-to-r from-primary-md to-primary-dk bg-clip-text text-transparent">
                        Organizations</h1>
                    <p class="text-gray-600 text-sm">Configure and manage organizations. if your request is rejected
                        wait for 1 hour to resend request.</p>
                </div>
                {{-- <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-blue-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input id="searchInput" type="text" placeholder="Search..."
                        class="pl-10 pr-4 py-2 w-full md:w-48 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-200 transition-all">
                </div> --}}
            {{-- </div> --}}
        {{-- </div> --}} --}}

        <!-- Filters -->
        {{-- <div class="flex flex-wrap gap-2">
            <button
                class="filter-tab px-3 py-1.5 text-sm rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-sm"
                data-filter="all">All</button>
            <button
                class="filter-tab px-3 py-1.5 text-sm rounded-lg bg-white/70 text-gray-700 hover:bg-white transition-all"
                data-filter="active">Active</button>
            <button
                class="filter-tab px-3 py-1.5 text-sm rounded-lg bg-white/70 text-gray-700 hover:bg-white transition-all"
                data-filter="pending">Pending</button>
            <button
                class="filter-tab px-3 py-1.5 text-sm rounded-lg bg-white/70 text-gray-700 hover:bg-white transition-all"
                data-filter="archived">Archived</button>
        </div> --}}

        <!-- Grid -->
        {{-- <div id="settingsGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($organizations as $org)
                <div class="group relative bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 setting-card"
                    data-category="active" data-title="{{ strtolower($org['name']) }}"
                    data-code="{{ strtolower($org['organization_code']) }}">

                    <!-- Logo or Icon -->
                    <div class="flex items-center justify-between mb-4">
                        @if (!empty($org['image']) && file_exists(public_path('storage/' . $org['image'])))
                            <img src="{{ asset('storage/' . $org['image']) }}" alt="{{ $org['name'] }} Logo"
                                class="w-auto h-10 object-cover rounded">
                        @else
                            <div
                                class="w-10 h-10 rounded-lg bg-gradient-to-tr from-primary-md to-primary-dk flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M4 3a2 2 0 000 4h12a2 2 0 000-4H4zM3 8a1 1 0 011-1h12a1 1 0 011 1v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Name + Code -->
                    <div class="mb-3">
                        <h3 class="text-base font-semibold text-gray-800 truncate">{{ $org['name'] }}</h3>
                        <p class="text-xs text-gray-500 tracking-wider uppercase">{{ $org['organization_code'] }}</p>
                    </div>

                    <!-- Location Details -->
                    <div class="text-xs text-gray-600 space-y-1 mb-4">
                        @if ($org['state'] || $org['country'] || $org['pin'])
                            <p><strong>State:</strong> {{ $org['state'] ?? '-' }}</p>
                            <p><strong>Country:</strong> {{ $org['country'] ?? '-' }}</p>
                            <p><strong>Pincode:</strong> {{ $org['pin'] ?? '-' }}</p>
                        @endif
                    </div>

                    <!-- Action Button -->
                    @php
                        $access = $accessMap[$org->id] ?? null;
                    @endphp

                    <div class="flex gap-2">
                        @if ( $access && $access['is_approved'])
                            <a href="{{ route('medical_rep.organization.view', $org->id) }}"
                                class="flex-1 text-center py-1.5 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:shadow-md transition">
                                View
                            </a>
                        @elseif ( $access &&$access['request_sent'] && !$access['is_rejected'])
                            <button
                                class="flex-1 py-1.5 text-sm font-medium text-white bg-yellow-400 rounded-lg cursor-not-allowed"
                                disabled>
                                Request Sent
                            </button>
                        @elseif ( $access && $access['is_rejected'])
                            <button
                                class="flex-1 py-1.5 text-sm font-medium text-white bg-red-500 rounded-lg cursor-not-allowed"
                                disabled>
                                Request Rejected
                            </button>
                        @else
                            <button data-org-id="{{ $org->id }}"
                                class="request-access-btn flex-1 py-1.5 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition">
                                Request Access
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results -->
        <div id="noResults" class="hidden text-center p-8 bg-white/90 rounded-xl">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <p class="text-gray-500">No organizations found</p>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const settingsGrid = document.getElementById('settingsGrid');
        const noResults = document.getElementById('noResults');
        const filterTabs = document.querySelectorAll('.filter-tab');
        let currentFilter = 'all';

        function filterCards() {
            const searchTerm = searchInput.value.toLowerCase();
            const cards = settingsGrid.querySelectorAll('.setting-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const code = card.getAttribute('data-code') || '';
                const category = card.getAttribute('data-category') || '';

                const matchesSearch = title.includes(searchTerm) || code.includes(searchTerm);
                const matchesFilter = currentFilter === 'all' || category === currentFilter;

                if (matchesSearch && matchesFilter) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                settingsGrid.classList.add('hidden');
            } else {
                noResults.classList.add('hidden');
                settingsGrid.classList.remove('hidden');
            }
        }

        searchInput.addEventListener('input', filterCards);

        filterTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                filterTabs.forEach(t => {
                    t.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-purple-600', 'text-white', 'shadow-lg');
                    t.classList.add('bg-white/70', 'text-gray-700');
                });

                tab.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-purple-600', 'text-white', 'shadow-lg');
                tab.classList.remove('bg-white/70', 'text-gray-700');

                currentFilter = tab.getAttribute('data-filter');
                filterCards();
            });
        });
    </script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.request-access-btn').on('click', function () {
                const $button = $(this);
                const orgId = $button.data('org-id');
                console.log(orgId);
                $.ajax({
                    url: `/medical-rep/organization/${orgId}/request-access`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $button.prop('disabled', true).text('Sending...');
                    },
                    success: function (response) {
                        $button
                            .removeClass('bg-blue-500 hover:bg-blue-600')
                            .addClass('bg-yellow-400 cursor-not-allowed')
                            .prop('disabled', true)
                            .text('Request Sent');
                    },
                    error: function () {
                        $button.prop('disabled', false).text('Request Access');
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>

</x-app-layout> --}}