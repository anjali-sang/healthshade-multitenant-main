<x-modal name="add-role-modal" width="w-100" height="h-auto" maxWidth="4xl">
    <header class="p-3">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add New Role') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Create a new role and set permissions once role is created.') }}
        </p>
    </header>
    <form wire:submit.prevent="createRole">
        <div class="space-y-12 p-3">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-3 gap-x-6 gap-y-8">
                    <div class="col-span-1">
                        <x-input-label for="role_name" :value="__('*Name')" />
                        <x-text-input id="role_name" wire:model="role_name" type="text" class="mt-1 block w-full"
                            required />
                        @error('role_name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-3">
                        <x-input-label for="role_description" :value="__('*Description')" />
                        <x-text-input id="role_description" wire:model="role_description" type="text"
                            class="mt-1 block w-full" required />
                        @error('role_description')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-4 mt-6" x-data="{ loading: false }">
            <x-primary-button class="min-w-24 flex justify-center items-center" x-data="{ loading: false }"
                x-on:click="loading = true; $wire.createRole().then(() => loading = false)" x-bind:disabled="loading">
                <!-- Button Text -->
                <span x-show="!loading">{{ __('Create') }}</span>
                <!-- Loader (Spinner) -->
                <span x-show="loading" class="absolute flex items-center justify-center">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                        </path>
                    </svg>
                </span>
            </x-primary-button>
            <x-secondary-button
                x-on:click="$dispatch('close-modal', 'add-role-modal')">{{ __('Cancel') }}</x-secondary-button>
        </div>
    </form>
</x-modal>