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
                                        {{ __('Manage Subscriptions') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Review and manage your current subscriptions below.') }}
                                    </p>
                                </div>
                                <div>
                                    <x-primary-button class="min-w-52 flex justify-center items-center"
                                        x-data="{ loading: false }"
                                        x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-subscription-modal'); loading = false }, 1000)"
                                        x-bind:disabled="loading">
                                        <!-- Button Text -->
                                        <span x-show="!loading">{{ __('+ Add Subscription') }}</span>

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
                    <livewire:tables.subscriptions-list />
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-subscription-modal" width="w-100" height="h-auto" maxWidth="2xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Add New Subscription') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Create a subscription plan that will fit users need. Ensure that all your details are accurate before proceeding.') }}
            </p>
        </header>
        <form wire:submit.prevent="createSubscription">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Subscription Name -->
                        <div class="sm:col-span-3">
                            <div>
                                <x-input-label for="name" :value="__('Subscription Name')" />
                                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                                    required />
                                @error('name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <x-input-label for="duration" :value="__('*Duration')" />
                            <select id="duration" wire:model="duration"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option selected value="3">{{ __('3 Months') }}</option>
                                <option value="6">{{ __('6 Months') }}</option>
                                <option value="12">{{ __('1 Year') }}</option>
                            </select>
                            @error('duration')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="price" :value="__('*Price')" />
                                <x-text-input id="price" wire:model="price" type="number" class="mt-1 block w-full"
                                    required />
                                @error('price')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Users -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="max_users" :value="__('*Users')" />
                                <x-text-input id="max_users" wire:model="max_users" type="number"
                                    class="mt-1 block w-full" required />
                                @error('max_users')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Locations -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="max_locations" :value="__('*Locations')" />
                                <x-text-input id="max_locations" wire:model="max_locations" type="number"
                                    class="mt-1 block w-full" required />
                                @error('max_locations')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                        <!-- Description -->
                        <div class="sm:col-span-6">
                            <div>
                                <x-input-label for="description" :value="__('Description ( 250 words)')" />
                                <textarea id="description" wire:model="description" rows="4"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required></textarea>
                                @error('description')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
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
                                        {{ __('This subscription plan will be available for users. Others will be able to purchase this subscription model.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button class="min-w-24 flex justify-center items-center"
                    x-on:click="loading = true; $wire.createSubscription().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <span x-show="!loading">{{ __('Create') }}</span>
                    <span x-show="loading" class="flex justify-center items-center w-full">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>

                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'add-subscription-modal')">{{ __('Cancel') }}</x-secondary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="edit-subscription-modal" width="w-100" height="h-auto" maxWidth="2xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit Subscription') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Edit the subscription plan details below.') }}
            </p>
        </header>
        <form wire:submit="updateSubscription">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Subscription Name -->
                        <div class="sm:col-span-3">
                            <div>
                                <x-input-label for="name" :value="__('Subscription Name')" />
                                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                                    required />
                                @error('name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <x-input-label for="duration" :value="__('*Duration')" />
                            <select id="duration" wire:model="duration"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option selected value="3 months">{{ __('3 Months') }}</option>
                                <option value="6 months">{{ __('6 Months') }}</option>
                                <option value="1 year">{{ __('1 Year') }}</option>
                            </select>
                            @error('duration')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input id="price" wire:model="price" type="number" class="mt-1 block w-full"
                                    required />
                                @error('price')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Users -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="max_users" :value="__('Users')" />
                                <x-text-input id="max_users" wire:model="max_users" type="number"
                                    class="mt-1 block w-full" required />
                                @error('max_users')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Locations -->
                        <div class="sm:col-span-2">
                            <div>
                                <x-input-label for="max_locations" :value="__('Locations')" />
                                <x-text-input id="max_locations" wire:model="max_locations" type="number"
                                    class="mt-1 block w-full" required />
                                @error('max_locations')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-6">
                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" wire:model="description" rows="4"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required></textarea>
                                @error('description')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Active checkbox -->
                        <div class="sm:col-span-3">
                            <div class="flex gap-3">
                                <div class="flex h-6 shrink-0 items-center">
                                    <input id="is_active" wire:model="is_active" @checked($is_active) type="checkbox"
                                        class="appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                </div>
                                <div class="text-sm/6">
                                    <x-input-label for="is_active" :value="__('Is Active')" />
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('This subscription plan will be available for users.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button class="min-w-24 flex justify-center items-center"
                    x-on:click="loading = true; $wire.updateSubscription().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <span x-show="!loading"> {{ __('Update') }}</span>
                    <span x-show="loading" class="flex justify-center items-center w-full">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span></x-primary-button>
                <x-secondary-button x-on:click="$dispatch('close-modal', 'edit-subscription-modal')">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </form>
    </x-modal>

</div>