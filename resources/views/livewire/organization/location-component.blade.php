<div>
    <div class="sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg w-full space-y-6 mb-6">
        <div class=" bg-white dark:bg-gray-800 rounded-lg p-4">
            <section class="w-full">
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">

                    @php
                        $org = auth()->user()->organization;
                        $subs = $org->subscription;
                        $maxLocations = $subs->max_locations;
                        $activeLocations = $org->locations()->where('is_active', true)->count() ?? 0;
                    @endphp
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Manage Locations') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Review and manage your locations details here below.') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Maximum ') }}{{ $maxLocations }}{{ __(' locations allowed under your subscription model.') }}
                        </p>


                    </div>
                    <div>

                        @if ($activeLocations < $maxLocations)
                            <x-primary-button class="min-w-44 flex justify-center items-center" x-data="{ loading: false }"
                                x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-location-modal'); loading = false }, 1000)"
                                x-bind:disabled="loading">
                                <!-- Button Text -->
                                <span x-show="!loading">{{ ('+ Add Location') }}</span>

                                <!-- Loader (Spinner) -->
                                <span x-show="loading" class="flex justify-center items-center w-full">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                                    </svg>
                                </span>
                            </x-primary-button>

                        @else
                            <p class="text-red-600 font-bold">Max limit exceeded for Locations. Please upgrade your plan.
                            </p>
                        @endif

                    </div>
                </header>
            </section>
        </div>
    </div>
    <div class="sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg w-full space-y-6">
        <div class="bg-white  dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div
                class="p-2 bg-white  dark:bg-gray-800 border-b border-gray-600 dark:border-gray-700 text-sm dark:text-gray-400 text-xs">
                <livewire:tables.locations-list />
            </div>
        </div>
    </div>
    <x-modal name="add-location-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Add Location') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Create a new Location/Clinic  and those fields marked as * are compulsory.') }}
            </p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Ensure that all your details are accurate before proceeding.') }}
            </p>
        </header>
        <form wire:submit.prevent="createLocation">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12 px-4 sm:px-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-input-label for="name" :value="__('*Name')" />
                            <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full" required />
                            @error('name')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label for="email" :value="__('*Email')" />
                            <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full"
                                required />
                            @error('email')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <div class="flex flex-wrap mt-1">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">+1</span>
                                <x-text-input id="phone" wire:model.lazy="phone" type="tel" maxlength="10"
                                    placeholder="123-456-7890" @blur="formatPhone($event)"
                                    class="block w-full rounded-none rounded-r-md" />

                            </div>
                            @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-input-label for="address" :value="__('*Address')" />
                            <x-text-input id="address" wire:model="address" type="text" class="mt-1 block w-full"
                                required />
                            @error('address')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label for="country" :value="__('*Country')" />

                            <select wire:model.live="selectedCountry"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">Select a Country</option>
                                @foreach (array_keys($countries) as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="sm:col-span-3">
                            <x-input-label for="state" :value="__('*State/Province')" />
                            <select wire:model="state"
                                class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">Select a State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2 sm:col-start-1">
                            <x-input-label for="city" :value="__('*City')" />
                            <x-text-input id="city" wire:model="city" type="text" class="mt-1 block w-full" required />
                            @error('city')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="pin" :value="__('*Zip/Postal Code')" />
                            <x-text-input id="pin" wire:model="pin" type="text" class="mt-1 block w-full" required />

                            @error('pin')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-4 sm:gap-x-6">
                <x-primary-button class="text-sm/6 font-semibold text-gray-900">Submit</x-primary-button>

                <x-secondary-button x-on:click="$dispatch('close-modal', 'add-location-modal')"
                    class="text-sm/6 font-semibold text-gray-900">Cancel</x-secondary-button>
            </div>
        </form>

    </x-modal>

    <x-modal name="edit-location-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Location') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Edit a new Location/Clinic and those fields marked as * are compulsory.') }}
            </p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Ensure that all your details are accurate before proceeding.') }}
            </p>
        </header>
        <form wire:submit.prevent="updateLocation">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12 px-4 sm:px-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Name -->
                        <div class="sm:col-span-3">
                            <x-input-label for="name" :value="__('*Name')" />
                            <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full" required />
                            @error('name')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div class="sm:col-span-3">
                            <x-input-label for="email" :value="__('*Email')" />
                            <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full"
                                required />
                            @error('email')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contact Phone -->
                        <div class="sm:col-span-2">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <div class="flex flex-wrap mt-1">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">+1</span>
                                <x-text-input id="phone" wire:model.lazy="phone" type="tel" maxlength="10"
                                    placeholder="123-456-7890" @blur="formatPhone($event)"
                                    class="block w-full rounded-none rounded-r-md" />

                            </div>
                            @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Address -->
                        <div class="sm:col-span-full">
                            <x-input-label for="address" :value="__('*Address')" />
                            <x-text-input id="address" wire:model="address" type="text" class="mt-1 block w-full"
                                required />
                            @error('address')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="sm:col-span-3">
                            <x-input-label for="country" :value="__('*Country')" />
                            <select wire:model.live="selectedCountry"
                                class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Select a Country</option>
                                @foreach (array_keys($countries) as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- State -->
                        <div class="sm:col-span-3">
                            <x-input-label for="state" :value="__('*State/Province')" />
                            <select wire:model="state" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Select a State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City -->
                        <div class="sm:col-span-2 sm:col-start-1">
                            <x-input-label for="city" :value="__('*City')" />
                            <x-text-input id="city" wire:model="city" type="text" class="mt-1 block w-full" required />
                            @error('city')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pin -->
                        <div class="sm:col-span-2">
                            <x-input-label for="pin" :value="__('*Zip/Postal Code')" />
                            <x-text-input id="pin" wire:model="pin" type="text" class="mt-1 block w-full" required />
                            @error('pin')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-4 sm:gap-x-6">
                <!-- Submit Button with Loader -->
                <x-primary-button
                    class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                    x-data="{ loading: false }"
                    x-on:click="loading = true; $wire.updateLocation().then(() => { loading = false; })"
                    x-bind:disabled="loading">
                    <!-- Button Text -->
                    <span x-show="!loading">{{ __('Submit') }}</span>

                    <!-- Loader (Spinner) -->
                    <span x-show="loading" class="flex justify-center items-center w-full">
                        <svg class="animate-spin h-4 w-4 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>


                <x-secondary-button x-on:click="$dispatch('close-modal', 'edit-location-modal')"
                    class="text-sm/6 font-semibold text-gray-900">Cancel</x-secondary-button>

                <x-danger-button wire:click="deleteLocation"
                    class="inline-flex items-center text-sm/6 font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                    <svg class="h-4 w-4 mr-1 text-white hover:text-red-100" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                    </svg>
                    Delete
                </x-danger-button>
            </div>
        </form>

    </x-modal>
    <!-- Notifications Container -->
    <div class="fixed top-24 right-4 z-50 space-y-2">
        @foreach($notifications as $notification)
            <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-init="
                                                        setTimeout(() => {
                                                            show = false;
                                                            $wire.removeNotification('{{ $notification['id'] }}');
                                                        }, 3000)
                                                    " x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
                class="{{ $notification['type'] === 'success' ? 'border-green-800 text-green-800  bg-green-300' : 'bg-red-300 border-red-800 text-red-800' }} border-l-4 x-6 py-6 px-4  shadow-lg bg-white dark:bg-gray-700">
                <p>{{ $notification['message'] }}</p>
            </div>
        @endforeach
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('openAddLocationModalDeferred', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-location-modal' }));
                }, 500);
            });
        });
    </script>
    <script>
        function formatPhone(event) {
            const input = event.target;
            const raw = input.value.replace(/\D/g, ''); // Strip all non-digits

            // Avoid formatting when deleting (allow user control)
            if (event.inputType && event.inputType.startsWith("delete")) {
                return;
            }

            let formatted = '';
            if (raw.length > 0) {
                formatted = raw.slice(0, 3);
            }
            if (raw.length >= 4) {
                formatted += '-' + raw.slice(3, 6);
            }
            if (raw.length >= 7) {
                formatted += '-' + raw.slice(6, 10);
            }

            input.value = formatted;

            // Livewire sync
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    </script>
</div>