<div>
    <div>
        <div class="py-4 lg:py-12">
            <div class="max-w-screen-5xl mx-auto px-1 sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="w-full">
                        <section class="w-full">
                            <header
                                class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Manage Manufacturer') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Review and manage your current manufacturer below.') }}
                                    </p>
                                </div>
                                <div>
                                    <x-primary-button class="min-w-52 flex justify-center items-center"
                                        x-data="{ loading: false }"
                                        x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-manufacturer-modal'); loading = false }, 1000)"
                                        x-bind:disabled="loading">
                                        <!-- Button Text -->
                                        <span x-show="!loading">{{ __('+ Add Manufacturer') }}</span>
                                        <!-- Loader (Spinner) -->
                                        <span x-show="loading" class="flex justify-center items-center w-full">
                                            <svg class="animate-spin h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                                            </svg>
                                        </span>
                                    </x-primary-button>
                                </div>
                            </header>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-screen-5xl mx-auto px-1 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div
                    class="p-6 bg-white  dark:bg-gray-800 border-b border-gray-600 dark:border-gray-700 text-sm dark:text-gray-400 text-xs">
                    <livewire:tables.organization.manufacturer-list />
                </div>
            </div>
        </div>

        <x-modal name="add-manufacturer-modal" width="w-100" height="h-auto" maxWidth="2xl">
            <!-- Modal Header -->
            <header class="p-3">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Add New Manufacturer') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Create a new manufacturer. Ensure that all details are accurate before proceeding.') }}
                </p>
            </header>

            <!-- Form -->
            <form wire:submit.prevent="createManufacturer">
                <div class="space-y-12 p-3">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Category Name -->
                            <div class="sm:col-span-3">
                                <x-input-label for="brand_name" :value="__('Manufacturer')" />
                                <x-text-input id="brand_name" wire:model="brand_name" type="text"
                                    class="mt-1 block w-full" required />
                                @error('brand_name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Image -->
                            <div class="sm:col-span-6">
                                <x-input-label for="new_brand_image" :value="__('*Logo')" />
                                <input type="file" id="new_brand_image" wire:model="new_brand_image"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('brand_image.*')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                <!-- Image Preview while adding -->
                                <div class="mt-4 grid grid-cols-3 gap-2">
                                    @if ($new_brand_image)
                                        <img src="{{ $new_brand_image->temporaryUrl() }}"
                                            class="w-24 h-24 object-cover rounded">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-4 mt-6">
                    <x-primary-button wire:click="createManufacturer">
                        {{ __('Create') }}
                    </x-primary-button>

                    <x-secondary-button wire:click="$dispatch('close-modal', 'add-manufacturer-modal')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="edit-manufacturer-modal" width="w-100" height="h-auto" maxWidth="2xl">
            <!-- Modal Header -->
            <header class="p-3">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Edit Manufacturer') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Update the category details and save changes.') }}
                </p>
            </header>

            <!-- Form -->
            <form wire:submit.prevent="updateManufacturer">
                <div class="space-y-12 p-3">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Category Name -->
                            <div class="sm:col-span-3">
                                <x-input-label for="brand_name" :value="__('Manufacturer')" />
                                <x-text-input id="brand_name" wire:model="brand_name" type="text"
                                    class="mt-1 block w-full" required />
                                @error('brand_name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="sm:col-span-6">
                                <x-input-label for="new_brand_image" :value="__('*Logo')" />
                                <input type="file" id="new_brand_image" wire:model="new_brand_image"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('new_brand_image')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                <!-- Image Preview -->
                                <div class="mt-4 grid grid-cols-3 gap-2">
                                    @if ($new_brand_image)
                                        <img src="{{ $new_brand_image->temporaryUrl() }}"
                                            class="w-24 h-24 object-cover rounded">
                                    @elseif ($existing_brand_image)
                                        <img src="{{ asset('storage/' . $existing_brand_image) }}"
                                            class="w-24 h-24 object-cover rounded">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-4 mt-6">
                    <x-primary-button wire:click="updateManufacturer">
                        {{ __('Update') }}
                    </x-primary-button>

                    <x-secondary-button wire:click="$dispatch('close-modal', 'edit-manufacturer-modal')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                </div>
            </form>
        </x-modal>

    </div>
</div>