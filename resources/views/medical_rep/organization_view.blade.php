{{-- <x-app-layout>
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8">
        <!-- Organization Overview Section -->
        <section
            class="flex justify-between items-center rounded-xl bg-gradient-to-r from-primary-md to-primary-dk p-8 dark:bg-gray-800 mt-3 shadow-lg">
            <!-- Organization Info -->
            <div class="bg-primary-dk dark:bg-gray-700/50 py-4 px-8 rounded-lg backdrop-blur-sm border border-white/20">
                <h1 class="text-3xl text-white dark:text-white mb-3 font-bold">{{ $organization->name }}</h1>
                <div class="text-white/90 dark:text-gray-200 space-y-2">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="text-sm">{{ $organization->email }}</span>
                    </div>
                    @if ($organization->phone)
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <span class="text-sm">{{ $organization->phone ?? 'Pending' }}</span>
                        </div>
                    @endif
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm">{{ $organization->city }}, {{ $organization->state }}</span>
                    </div>
                </div>
            </div>

            <!-- Organization Logo -->
            <div class="flex items-center justify-center">
                @if (!empty($organization?->image))
                    <div class="p-2 bg-white/20 rounded-lg">
                        <img src="{{ asset('storage/' . $organization->image) }}" alt="Organization Logo"
                            class="w-auto h-24 object-cover rounded shadow-lg">
                    </div>
                @else
                    <div class="w-24 h-24 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5">
                            </path>
                        </svg>
                    </div>
                @endif
            </div>
        </section>

        <!-- Locations Section -->
        <section>
            <div>
                @if($organization->locations->isEmpty())
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-20">
                        <div class="relative mb-8">
                            <div
                                class="absolute inset-0 bg-primary-lt/20 dark:bg-primary-dk/30 rounded-full blur-xl animate-pulse">
                            </div>
                            <div
                                class="relative bg-gradient-to-br from-primary-lt/10 to-primary-md/10 dark:from-primary-dk/20 dark:to-primary-md/20 p-8 rounded-full border border-primary-lt/20 dark:border-primary-dk/30">
                                <svg class="w-16 h-16 text-primary-md" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-3">
                            No Locations Found
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm text-lg leading-relaxed">
                            This organization doesn't have any registered locations yet. Add your first location to get
                            started.
                        </p>
                        <div class="mt-6">
                            <div
                                class="h-1 w-20 bg-gradient-to-r from-primary-lt via-primary-md to-primary-dk rounded-full">
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Locations List View -->
                    <div class="space-y-4">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Locations</h3>
                            <div
                                class="h-1 w-16 bg-gradient-to-r from-primary-lt via-primary-md to-primary-dk rounded-full">
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            @foreach($organization->locations as $index => $location)
                                <div
                                    class="group relative {{ $index > 0 ? 'border-t border-gray-200 dark:border-gray-700' : '' }}">
                                    <!-- Hover Background Effect -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-primary-lt/5 via-primary-md/5 to-primary-dk/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    <!-- List Item Content -->
                                    <div
                                        class="relative p-6 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-all duration-300">
                                        <div class="flex items-start justify-between">
                                            <!-- Location Info -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-3 mb-3">

                                                    <!-- Location Name -->
                                                    <div>
                                                        <h4
                                                            class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-primary-md transition-colors duration-300">
                                                            {{ $location->name }}
                                                        </h4>
                                                        <div
                                                            class="h-0.5 w-8 bg-gradient-to-r from-primary-lt to-primary-md rounded-full group-hover:w-12 transition-all duration-300 mt-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Location Details Grid -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    <!-- Address -->
                                                    <div class="flex items-start space-x-2">
                                                        <div class="p-1 bg-gray-100 dark:bg-gray-700 rounded mt-0.5">
                                                            <svg class="w-3 h-3 text-gray-500 dark:text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Address
                                                            </p>
                                                            <p class="text-sm text-gray-600 dark:text-gray-300 truncate">
                                                                {{ $location->address }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $location->city }}, {{ $location->state }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Email -->
                                                    @if($location->email)
                                                        <div class="flex items-start space-x-2">
                                                            <div class="p-1 bg-gray-100 dark:bg-gray-700 rounded mt-0.5">
                                                                <svg class="w-3 h-3 text-gray-500 dark:text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Email
                                                                </p>
                                                                <a href="mailto:{{ $location->email }}"
                                                                    class="text-sm text-primary-md hover:text-primary-dk dark:text-primary-lt dark:hover:text-primary-md transition-colors duration-200 hover:underline truncate block">
                                                                    {{ $location->email }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Phone -->
                                                    @if($location->phone)
                                                        <div class="flex items-start space-x-2">
                                                            <div class="p-1 bg-gray-100 dark:bg-gray-700 rounded mt-0.5">
                                                                <svg class="w-3 h-3 text-gray-500 dark:text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Phone
                                                                </p>
                                                                <a href="tel:{{ $location->phone }}"
                                                                    class="text-sm text-primary-md hover:text-primary-dk dark:text-primary-lt dark:hover:text-primary-md transition-colors duration-200 hover:underline">
                                                                    {{ $location->phone }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Action Button -->
                                            {{-- <div class="ml-4 flex-shrink-0">
                                                <button
                                                    class="group/btn relative overflow-hidden bg-gradient-to-r from-primary-md to-primary-dk hover:from-primary-lt hover:to-primary-md text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-sm hover:shadow-md">
                                                    <div
                                                        class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-500">
                                                    </div>
                                                    <div class="relative flex items-center space-x-2">
                                                        <svg class="w-4 h-4 transform group-hover/btn:rotate-12 transition-transform duration-300"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        <span class="text-sm">View</span>
                                                        <svg class="w-3 h-3 transform group-hover/btn:translate-x-1 transition-transform duration-300"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </div> --}}
                                        {{-- </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div> --}}
{{-- </x-app-layout> --}} --}}