<div class="p-6 px-1 lg:px-4 pt-4 lg:pt-12">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-6 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Customers') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Manage all customers listed within your organization.') }}
                </p>
            </div>

            <x-primary-button class="min-w-36 flex justify-center items-center" x-data="{ loading: false }"
                @click="loading = true; $wire.call('openCustomerModal').then(() => loading = false)"
                x-bind:disabled="loading">
                <!-- Button Text -->
                <span x-show="!loading">{{ __('+ Add Customer') }}</span>
                <!-- Loader -->
                <span x-show="loading" class="flex items-center">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.48 0 0 6.48 0 12h4z" />
                    </svg>
                </span>
            </x-primary-button>
        </div>

        <!-- Modal -->
        @include('livewire.organization.customers.modals.customer-modal')

        <!-- Customer List Table -->
        <div class="px-6 py-4 text-xs">
            <livewire:tables.organization.customer.customer-list />
        </div>
    </div>
</div>