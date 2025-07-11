<div>
    <!-- Ticket List Header -->
    <div class="max-w-10xl mx-auto">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-5">
            <section class="w-full border-b-2 pb-4 mb-6">
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Tickets') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('View and manage your tickets here.') }}
                        </p>
                    </div>
                    <div>
                        <x-primary-button class="min-w-52 flex justify-center items-center" x-data="{ loading: false }"
                            x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-ticket-modal'); loading = false }, 1000)"
                            x-bind:disabled="loading">
                            <!-- Button Text -->
                            <span x-show="!loading">{{ __('Generate Ticket') }}</span>
                            <!-- Loader (Spinner) -->
                            <span x-show="loading" class="flex justify-center items-center w-full">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
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
            <div class="text-xs">
                <livewire:tables.user.ticket-list />
            </div>
        </div>
    </div>
    @include('livewire.user.tickets.modals.add-ticket-modal')
    @include('livewire.user.tickets.modals.show-ticket-modal')
</div>
