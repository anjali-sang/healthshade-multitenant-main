<div>
    <div>
        <div class="py-12">
            <div class="max-w-screen-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="w-full">
                        <section class="w-full">
                            <header
                                class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Manage Suppliers') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Review and manage suppliers required for creating Orders.') }}
                                    </p>
                                </div>
                                <div>
                                    <x-primary-button
                                        class="flex justify-center items-center min-w-44 px-4 py-2 bg-primary-md dark:bg-primary-md border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 hover:bg-primary-lt dark:hover:bg-primary-lt focus:bg-primary-dk dark:focus:bg-primary-dk active:bg-primary-dk dark:active:bg-primary-dk focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                        x-data="{ loading: false }"
                                        x-on:click="loading = true; $dispatch('open-modal', 'add-supplier-modal'); setTimeout(() => loading = false, 1000)"
                                        x-bind:disabled="loading">
                                        <!-- Button Text -->
                                        <span x-show="!loading">{{ __('+ Add Supplier') }}</span>
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
        <div class="max-w-screen-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div
                    class="p-6 bg-white  dark:bg-gray-800 border-b border-gray-600 dark:border-gray-700 text-sm dark:text-gray-400 text-xs">
                    <livewire:tables.suppliers-list />
                </div>
            </div>
        </div>
    </div>
    <style>
        .tooltipslug {
            position: relative;
            display: inline-block;
            cursor: pointer;
            background-color: #ddd;
            color: black;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            font-weight: bold;
        }

        .tooltiptext {
            visibility: hidden;
            width: 220px;
            background-color: #555;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            /* Show above the icon */
            left: 50%;
            margin-left: -110px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltipslug:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>

    <x-modal name="add-supplier-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Add New Supplier') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Create a new supplier for your products. Ensure that all your details are accurate before proceeding.') }}
            </p>
        </header>
        <form wire:submit.prevent="createSupplier">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Unit Name -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="supplier_name" :value="__('*Name')" />
                                <x-text-input id="supplier_name" wire:model="supplier_name" type="text"
                                    class="mt-1 block w-full" required />
                                @error('supplier_name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="sm:col-span-4">
                            <x-input-label for="supplier_email" :value="__('*Email')" />
                            <x-text-input id="supplier_email" wire:model="supplier_email" type="email"
                                class="mt-1 block w-full" required />
                            @error('supplier_email')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Abbreviation -->
                        <div class="sm:col-span-2">
                            <div class="flex justify-between">
                                <x-input-label for="supplier_slug" :value="__('*Slug')" />
                                <div class="tooltipslug">?
                                    <span class="tooltiptext">Choose a Unique name with no spaces.</span>
                                </div>
                            </div>
                            <x-text-input id="supplier_slug" wire:model="supplier_slug" type="text"
                                class="mt-1 block w-full" required />
                            @error('supplier_slug')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <x-input-label for="supplier_phone" :value="__('Phone Number')" />
                            <div class="flex mt-1">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">+1</span>
                                <x-text-input id="supplier_phone" wire:model.lazy="supplier_phone" type="tel"
                                    pattern="[0-9]{10}" maxlength="10" placeholder="1234567890"
                                    class="block w-full rounded-none rounded-r-md" />
                            </div>
                            @error('supplier_phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <!-- Abbreviation -->
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_vat" :value="__('Vat Number')" />
                            <x-text-input id="supplier_vat" wire:model="supplier_vat" type="text"
                                class="mt-1 block w-full" required />
                            @error('supplier_vat')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="sm:col-span-4">
                            <x-input-label for="supplier_address" :value="__('*Address')" />
                            <x-text-input id="supplier_address" wire:model="supplier_address" type="text"
                                class="mt-1 block w-full" required />
                            @error('supplier_address')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_city" :value="__('*City')" />
                            <x-text-input id="supplier_city" wire:model="supplier_city" type="text"
                                class="mt-1 block w-full" required />
                            @error('supplier_city')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="country" :value="__('*Country')" />
                            <select wire:model.live="selectedCountry"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">Select a Country</option>
                                @foreach (array_keys($countries) as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                            @error('supplier_country')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_state" :value="__('*State/Province')" />
                            <select wire:model="supplier_state"
                                class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">Select a State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                            @error('supplier_state')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_zip" :value="__('*Zip/Postal code')" />
                            <x-text-input id="supplier_zip" wire:model="supplier_zip" type="text"
                                class="mt-1 block w-full" required />
                            @error('supplier_zip')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Active checkbox -->
                        <div class="sm:col-span-3">
                            <div class="flex gap-3">
                                <div class="flex h-6 shrink-0 items-center">
                                    <input id="is_active" wire:model="is_active" type="checkbox"
                                        class="appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                </div>
                                <div class="text-sm/6">
                                    <x-input-label for="is_active" :value="__('Is Active')" />
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('This Is_Active feature is used to disable or enable supplier') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button class="min-w-24"
                    x-on:click="loading = true; $wire.createSupplier().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <span x-show="!loading">{{ __('Create') }}</span>
                    <span x-show="loading" class="flex justify-center items-center w-full">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>
                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'add-supplier-modal')">{{ __('Cancel') }}</x-secondary-button>
            </div>
        </form>
    </x-modal>


    <x-modal name="edit-supplier-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Supplier') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Edit supplier details below. Ensure all information is accurate before saving.') }}
            </p>
        </header>
        <form wire:submit.prevent="updateSupplier">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Supplier Name -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="supplier_name" :value="__('*Name')" />
                                <x-text-input id="supplier_name" wire:model="supplier_name" type="text"
                                    class="mt-1 block w-full" required />
                                @error('supplier_name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Supplier Email -->
                        <div class="sm:col-span-4">
                            <x-input-label for="supplier_email" :value="__('*Email')" />
                            <x-text-input id="supplier_email" wire:model="supplier_email" type="email"
                                class="mt-1 block w-full" required />
                            @error('supplier_email')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="sm:col-span-2" hidden>
                            <x-input-label for="supplier_slug" :value="__('*Slug')" />
                            <x-text-input id="supplier_slug" wire:model="supplier_slug" type="text"
                                class="mt-1 block w-full" required />
                            @error('supplier_slug')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="supplier_phone" :value="__('Phone')" />
                                <x-text-input id="supplier_phone" wire:model="supplier_phone" type="number"
                                    class="mt-1 block w-full" />
                                @error('supplier_phone')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- VAT Number -->
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_vat" :value="__('VAT Number')" />
                            <x-text-input id="supplier_vat" wire:model="supplier_vat" type="text"
                                class="mt-1 block w-full" />
                            @error('supplier_vat')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="sm:col-span-4">
                            <x-input-label for="supplier_address" :value="__('Address')" />
                            <x-text-input id="supplier_address" wire:model="supplier_address" type="text"
                                class="mt-1 block w-full" />
                            @error('supplier_address')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_city" :value="__('City')" />
                            <x-text-input id="supplier_city" wire:model="supplier_city" type="text"
                                class="mt-1 block w-full" />
                            @error('supplier_city')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_country" :value="__('Country')" />
                            <select wire:model.live="selectedCountry"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a Country</option>
                                @foreach (array_keys($countries) as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                            @error('supplier_country')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- State -->
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_state" :value="__('*State/Province')" />
                            <select wire:model="supplier_state"
                                class="border-gray-300 w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                            @error('supplier_state')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Zip -->
                        <div class="sm:col-span-2">
                            <x-input-label for="supplier_zip" :value="__('Zip/Postal Code')" />
                            <x-text-input id="supplier_zip" wire:model="supplier_zip" type="text"
                                class="mt-1 block w-full" />
                            @error('supplier_zip')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Active checkbox -->
                        <div class="sm:col-span-3">
                            <div class="flex gap-3">
                                <div class="flex h-6 shrink-0 items-center">
                                    <input id="is_active" wire:model="is_active" type="checkbox" @checked($is_active)
                                        class="appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                </div>
                                <div class="text-sm/6">
                                    <x-input-label for="is_active" :value="__('Is Active')" />
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('This Is_Active feature is used to disable or enable supplier ') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button class="min-w-24"
                    x-on:click="loading = true; $wire.updateSupplier().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <span x-show="!loading">{{ __('Update') }}</span>
                    <span x-show="loading" class="flex justify-center items-center w-full">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>
                <x-secondary-button x-on:click="$dispatch('close-modal', 'edit-supplier-modal')">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </form>
    </x-modal>


    {{-- <x-modal name="edit-unit-modal" width="w-100" height="h-auto" maxWidth="5xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Unit') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Edit the subscription plan details below.') }}
            </p>
        </header>
        <form wire:submit="updateUnit">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Subscription Name -->
                        <div class="sm:col-span-3">
                            <div>
                                <x-input-label for="unit_name" :value="__('Unit Name')" />
                                <x-text-input id="unit_name" wire:model="unit_name" type="text"
                                    class="mt-1 block w-full" required />
                                @error('name')
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="unit_code" :value="__('Unit Code')" />
                                <x-text-input id="unit_code" wire:model="unit_code" type="text"
                                    class="mt-1 block w-full" required />
                                @error('unit_code')
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button x-on:click="loading = true; $wire.updateUnit().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <span x-show="!loading"> {{ __('Update') }}</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span></x-primary-button>
                <x-secondary-button x-on:click="$dispatch('close-modal', 'edit-unit-modal')">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </form>
    </x-modal> --}}

</div>