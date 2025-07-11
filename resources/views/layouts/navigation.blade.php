<nav x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 z-30 w-full fixed border-b border-gray-200 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex tems-center justify-between h-16 md:h-20">
            <!-- Settings Dropdown -->
            <div class="flex justify-center items-center">
                @if (!empty(auth()->user()->organization?->image))
                    <img src="{{ asset('storage/' . auth()->user()->organization?->image) }}" alt="Organization Logo"
                        class="max-h-12 max-w-[150px] object-contain">
                @else
                    <a href="/organization/catalog">
                        <x-application-logo class="w-auto h-12 fill-current text-gray-500" />
                    </a>
                @endif
            </div>
            <div class="hidden md:flex sm:items-center sm:ms-6">
                <!-- Theme Toggle Button -->
                <div class="mr-3">
                    <livewire:cart-icon />
                </div>
                {{-- @php
                    $pendingRequests = \App\Models\MedrepOrgAccess::where('org_id', auth()->user()->organization_id)
                        ->where('request_sent', true)
                        ->where('is_approved', false)
                        ->where('is_rejected', false)
                        ->count();
                @endphp --}}
                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-3">
                                <!-- Avatar Wrapper -->
                                <span class="relative">
                                    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}"
                                        class="w-10 h-10 rounded-full border-2 border-transparent peer-checked:border-blue-500 cursor-pointer"
                                        alt="Avatar">

                                    {{-- @if ($pendingRequests > 0)
                                        <span
                                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-semibold rounded-full px-1.5 py-0.5">
                                            {{ $pendingRequests }}
                                        </span>
                                    @endif --}}
                                </span>

                                <span>{{ Auth::user()->name }}</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('ticket.index')">
                            {{ __('Tickets') }}
                        </x-dropdown-link>
                        {{-- <x-dropdown-link :href="route('medical_rep.requests')"
                            class="relative flex items-center justify-between">
                            <span>{{ __('Requests') }}</span>
                            @if ($pendingRequests > 0)
                                <span
                                    class="ml-2 inline-flex items-center justify-center bg-red-500 text-white text-xs font-semibold rounded-full px-1.5 py-0.5">
                                    {{ $pendingRequests }}
                                </span>
                            @endif
                        </x-dropdown-link> --}}
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="#" onclick="confirmLogout(event)">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu - Left Side Drawer -->
    <div :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
        class="fixed top-0 left-0 w-4/5 sm:w-1/2 h-full bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out z-50 overflow-y-auto md:hidden">

        <!-- Close Button -->
        <div class="flex justify-end p-4">
            <button @click="open = false"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- User Profile Section -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4 space-y-4">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <div class="flex items-center content-start gap-3">
                        <div>
                            <img src="{{ asset('avatars/' . Auth::user()->avatar) }}"
                                class="w-10 h-10 rounded-full border-2 border-transparent peer-checked:border-blue-500 cursor-pointer"
                                alt="Avatar">
                        </div>
                        <div>
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </x-responsive-nav-link>

                <!-- Include Navigation Items Based on Role -->
                {{-- @if(auth()->user()->role_id === '1')
                    <!-- @include('layouts.mobile_navigations.admin-side-navigation') -->
                @else --}}
                    @include('layouts.mobile_navigations.user-side-navigation')
                {{-- @endif --}}
                <div class="mt-3 space-y-1">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <x-dropdown-link href="#" onclick="confirmLogout(event)">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay to close drawer when clicking outside -->
    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            if (confirm("Are you sure you want to log out?")) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
</nav>