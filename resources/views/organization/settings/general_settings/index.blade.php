<x-app-layout>
    <livewire:organization.edit-org-component />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- General Settings Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">General Settings</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure your organization preferences</p>
            </div>

            <form method="POST"
                action="{{ route('organization.settings.general_settings.update', ['organization' => $organization->id]) }}">
                @csrf
                @method('PUT')

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Currency -->
                        <div>
                            <label for="currency"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Currency <span class="text-red-500">*</span>
                            </label>
                            <select name="currency" id="currency"
                                class="w-full px-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1">
                                <option value="$" {{ $organization?->currency == '$' ? 'selected' : '' }}>$ USD</option>
                                <option value="€" {{ $organization?->currency == '€' ? 'selected' : '' }}>€ EUR</option>
                                <option value="£" {{ $organization?->currency == '£' ? 'selected' : '' }}>£ GBP</option>
                                <option value="₹" {{ $organization?->currency == '₹' ? 'selected' : '' }}>₹ INR</option>
                            </select>
                            @error('currency')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Timezone -->
                        <div>
                            <label for="timezone"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Time Zone <span class="text-red-500">*</span>
                            </label>
                            <select id="timezone" name="timezone"
                                class="w-full  px-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1">
                                <option value="UTC" {{ $organization?->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ $organization?->timezone == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                <option value="America/Los_Angeles" {{ $organization?->timezone == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                <option value="Asia/Kolkata" {{ $organization?->timezone == 'Asia/Kolkata' ? 'selected' : '' }}>Indian Standard Time</option>
                            </select>
                            @error('timezone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Format -->
                        <div>
                            <label for="date_format"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date
                                Format</label>
                            <select name="date_format" id="date_format"
                                class="w-full px-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1">
                                <option value="Y-m-d" {{ $organization->date_format == 'Y-m-d' ? 'selected' : '' }}>
                                    YYYY-MM-DD</option>
                                <option value="d-m-Y" {{ $organization->date_format == 'd-m-Y' ? 'selected' : '' }}>
                                    DD-MM-YYYY</option>
                                <option value="m/d/Y" {{ $organization->date_format == 'm/d/Y' ? 'selected' : '' }}>
                                    MM/DD/YYYY</option>
                                <option value="d/m/Y" {{ $organization->date_format == 'd/m/Y' ? 'selected' : '' }}>
                                    DD/MM/YYYY</option>
                                <option value="m-d-Y" {{ $organization->date_format == 'm-d-Y' ? 'selected' : '' }}>
                                    MM-DD-YYYY</option>
                                <option value="d.m.Y" {{ $organization->date_format == 'd.m.Y' ? 'selected' : '' }}>
                                    DD.MM.YYYY</option>
                                <option value="m.d.Y" {{ $organization->date_format == 'm.d.Y' ? 'selected' : '' }}>
                                    MM.DD.YYYY</option>
                                <option value="Y/m/d" {{ $organization->date_format == 'Y/m/d' ? 'selected' : '' }}>
                                    YYYY/MM/DD</option>
                            </select>
                            @error('date_format')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time Format -->
                        <div>
                            <label for="time_format"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time
                                Format</label>
                            <select name="time_format" id="time_format"
                                class="w-full px-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-md focus:ring-primary-md focus:ring-1">
                                <option value="H:i" {{ $organization->time_format == 'H:i' ? 'selected' : '' }}>24-hour
                                    (HH:MM)</option>
                                <option value="h:i A" {{ $organization->time_format == 'h:i A' ? 'selected' : '' }}>
                                    12-hour (hh:MM AM/PM)</option>
                            </select>
                            @error('time_format')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex flex-col sm:flex-row justify-end items-center gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="px-4 sm:px-6 py-2 bg-primary-md hover:bg-primary-dk text-white rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Theme Settings Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Theme Settings</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Customize your organization's
                            appearance</p>
                    </div>
                    <livewire:theme-selector />
                </div>
            </div>
        </div>

        <!-- Display Mode Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 sm:gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Display Mode</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Choose your preferred display mode</p>
                    </div>

                    <div x-data="{ theme: localStorage.getItem('theme') || 'light' }"
                        class="flex flex-wrap items-center justify-center sm:justify-start bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">

                        <!-- Light Mode Button -->
                        <button x-on:click="
                            theme = 'light';
                            localStorage.setItem('theme', theme);
                            document.documentElement.classList.remove('dark');
                        " x-bind:class="theme === 'light' ? 'bg-white shadow-sm text-primary-md' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium text-sm">Light</span>
                        </button>

                        <!-- Dark Mode Button -->
                        <button x-on:click="
                            theme = 'dark';
                            localStorage.setItem('theme', theme);
                            document.documentElement.classList.add('dark');
                        " x-bind:class="theme === 'dark' ? 'bg-gray-800 shadow-sm text-primary-lt' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="flex items-center gap-2 px-3 py-2 rounded-md transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <span class="font-medium text-sm">Dark</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>