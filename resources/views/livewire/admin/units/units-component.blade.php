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
                                        {{ __('Manage Units') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Review and manage unit required for Products.') }}
                                    </p>
                                </div>
                                <div>
                                    <x-primary-button class="min-w-36 flex justify-center items-center"
                                        x-data="{ loading: false }"
                                        x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-unit-modal'); loading = false }, 1000)"
                                        x-bind:disabled="loading">
                                        <!-- Button Text -->
                                        <span x-show="!loading">{{ __('+ Add Unit') }}</span>

                                        <!-- Loader (Spinner) -->
                                        <span x-show="loading" class="flex justify-center items-center w-full">
                                            <svg class="animate-spin h-4 w-4 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
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
                    <livewire:tables.unit-list />
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-unit-modal" width="w-100" height="h-auto" maxWidth="2xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Add New Unit') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Create a new unit of measurement for your product. Ensure that all your details are accurate before proceeding.') }}
            </p>
        </header>
        <form wire:submit.prevent="createUnit">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Unit Name -->
                        <div class="sm:col-span-3">
                            <div>
                                <x-input-label for="unit_name" :value="__('Unit Name')" />
                                <x-text-input id="unit_name" wire:model="unit_name" type="text"
                                    class="mt-1 block w-full" required />
                                @error('unit_name')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Abbreviation -->
                        <div class="sm:col-span-3">
                            <x-input-label for="unit_code" :value="__('Unit Code')" />
                            <x-text-input id="unit_code" wire:model="unit_code" type="text" class="mt-1 block w-full"
                                required />
                            @error('unit_code')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button class="min-w-24 flex justify-center items-center" x-data="{ loading: false }"
                    x-on:click="loading = true; $wire.createUnit().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <!-- Button Text -->
                    <span x-show="!loading">{{ __('Create') }}</span>

                    <!-- Loader (Spinner) -->
                    <span x-show="loading" class="absolute flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>
                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'add-unit-modal')">{{ __('Cancel') }}</x-secondary-button>
            </div>
        </form>
    </x-modal>


    <x-modal name="edit-unit-modal" width="w-100" height="h-auto" maxWidth="2xl">
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
                        <!-- Active checkbox -->
                        <div class="sm:col-span-3">
                            <div class="flex gap-3">
                                <div class="flex h-6 shrink-0 items-center">
                                    <input id="is_active" wire:model="is_active" @checked($is_active)
                                        type="checkbox"
                                        class="appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                </div>
                                <div class="text-sm/6">
                                    <x-input-label for="is_active" :value="__('Is Active')" />
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('This is used to  make  active and inactive  the units ') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
                <x-primary-button class="min-w-24 flex justify-center items-center" x-data="{ loading: false }"
                    x-on:click="loading = true; $wire.updateUnit().then(() => loading = false)"
                    x-bind:disabled="loading">
                    <!-- Button Text -->
                    <span x-show="!loading">{{ __('Update') }}</span>

                    <!-- Loader (Spinner) -->
                    <span x-show="loading" class="absolute flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                        </svg>
                    </span>
                </x-primary-button>
                <x-danger-button wire:click="deleteUnit('{{ $unitId }}')"
                    class="inline-flex items-center text-sm/6 font-semibold text-white-600 hover:text-white-800 transition-colors">
                    <svg class="h-4 w-4 mr-1 text-white-600 hover:text-white-800" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                    </svg>
                    Delete
                </x-danger-button>

                <x-secondary-button x-on:click="$dispatch('close-modal', 'edit-unit-modal')">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </form>
    </x-modal>

</div>
