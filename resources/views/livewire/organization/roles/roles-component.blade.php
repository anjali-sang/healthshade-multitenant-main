<div>
    <div class="py-4">
        <div class="max-w-10xl mx-auto px-4">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-5">
                <section class="w-full border-b-2 pb-4 mb-6 flex justify-center items-center">
                    <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Manage Roles and permissons') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Manage roles and permissons for your organization.') }}
                            </p>
                        </div>
                    </header>
                    <div class="flex justify-between items-center gap-3">
                        <x-primary-button class="min-w-36 flex justify-center items-center" x-data="{ loading: false }"
                            x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-role-modal'); loading = false }, 1000)"
                            x-bind:disabled="loading">
                            <!-- Button Text -->
                            <span x-show="!loading">{{ __('+ Add Role') }}</span>
                            <!-- Loader (Spinner) -->
                            <span x-show="loading" class="flex justify-center items-center w-full">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                                    </path>
                                </svg>
                            </span>
                        </x-primary-button>
                    </div>
                </section>
                <div class="text-xs">
                    <livewire:tables.organization.roles-list />
                </div>
            </div>
        </div>
    </div>
    @include('livewire.organization.roles.modals.add-role-modal')
    @include('livewire.organization.roles.modals.edit-role-modal')
    @include('livewire.organization.roles.modals.set-permissions-modal')
    <div class="fixed top-24 right-4 z-50 space-y-2">
        @foreach ($notifications as $notification)
            <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }"
                x-init="setTimeout(() => {show = false;$wire.removeNotification({{$notification['id'] }}');}, 3000"
                x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
                class="{{ $notification['type'] === 'success' ? 'border-green-800 text-green-800  bg-green-300' : 'bg-red-300 border-red-800 text-red-800' }} border-l-4 x-6 py-6 px-4  shadow-lg bg-white dark:bg-gray-700">
                <p>{{ $notification['message'] }}</p>
            </div>
        @endforeach
    </div>
</div>