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
                                        {{ __('Manage Organization') }}
                                    </h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('Review and manage organization as required.') }}
                                    </p>
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
                    {{-- <livewire:tables.admin-organization /> --}}
                    {{-- <livewire:tables.suppliers-list /> --}}
                    <livewire:organization-list />
                </div>
            </div>
        </div>

        <!-- Edit Organization Modal -->
        {{-- <x-modal name="edit-organization-modal" width="w-100" height="h-auto" maxWidth="4xl">
            <header class="p-3">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Edit Organization') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Edit the Organization details, and those fields marked with * are compulsory.') }}
                </p>
            </header>
            <form wire:submit.prevent="updateOrganization">
                <div class="space-y-12 p-3">
                    <div class="border-b border-gray-900/10 pb-12 px-12">
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Name -->
                            <div class="sm:col-span-4">
                                <x-input-label for="name" :value="__('*Organization Name')" />
                                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                                    required />
                                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Subscription -->
                            <div class="sm:col-span-3">
                                <x-input-label for="subscription_id" :value="__('*Subscription')" />
                                <select wire:model="subscription_id" id="subscription_id" class="block w-full mt-1"
                                    required>
                                    <option value="">Select Subscription</option>
                                    @foreach ($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}">{{ $subscription->name }}</option>
                                    @endforeach
                                </select>
                                @error('subscription_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-span-2">
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" wire:model="phone" type="number" class="mt-1 block w-full"
                                    required />
                                @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-span-full">
                                <x-input-label for="address" :value="__('*Address')" />
                                <x-text-input id="address" wire:model="address" type="text" class="mt-1 block w-full"
                                    required />
                                @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <!-- Submit Button with Loader -->
                    <x-primary-button
                        class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                        x-data="{ loading: false }"
                        x-on:click="loading = true; $wire.updateOrganization().then(() => { loading = false; })"
                        x-bind:disabled="loading">
                        <span x-show="!loading">{{ __('Update Organization') }}</span>
                        <span x-show="loading" class="flex justify-center items-center w-full">
                            <svg class="animate-spin h-4 w-4 text-gray-900" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                            </svg>
                        </span>
                    </x-primary-button>

                    <!-- Cancel Button -->
                    <x-secondary-button x-on:click="$dispatch('close-modal', 'edit-organization-modal')"
                        class="text-sm/6 font-semibold text-gray-900">{{ __('Cancel') }}</x-secondary-button>

                    <!-- Delete Button -->
                    <x-danger-button wire:click="deleteOrganization" class="text-sm/6 font-semibold text-red-600">{{
                        __('Delete') }}</x-danger-button>
                </div>
            </form>
        </x-modal> --}}
        <x-modal name="edit-organization-modal" width="w-100" height="h-auto" maxWidth="4xl">
            <header class="p-3">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Edit Organization') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Edit the Organization details, and those fields marked with * are compulsory.') }}
                </p>
            </header>
            <form wire:submit.prevent="updateOrganization">
                <div class="space-y-12 p-3">
                    <div class="border-b border-gray-900/10 pb-12 px-12">
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Name -->
                            <div class="sm:col-span-3">
                                <x-input-label for="name" :value="__('*Organization Name')" />
                                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                                    required />
                                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-3">
                                <x-input-label for="email" :value="__('*Email')" />
                                <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full"
                                    required />
                                @error('email')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subscription -->
                            <div class="sm:col-span-3">
                                <x-input-label for="subscription_id" :value="__('*Subscription')" />
                                <select wire:model="subscription_id" id="subscription_id" class="block w-full mt-1"
                                    required>
                                    <option value="">Select Subscription</option>
                                    @foreach ($subscriptions as $subscription)
                                        <option value="{{ $subscription->id }}">{{ $subscription->name }}</option>
                                    @endforeach
                                </select>
                                @error('subscription_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-span-2">
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <div class="flex mt-1">
                                    <span
                                        class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">+1</span>
                                    <x-text-input id="phone" wire:model.lazy="phone" type="tel" pattern="[0-9]{10}"
                                        maxlength="10" placeholder="1234567890"
                                        class="block w-full rounded-none rounded-r-md" />
                                </div>
                                @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-span-full">
                                <x-input-label for="address" :value="__('*Address')" />
                                <x-text-input id="address" wire:model="address" type="text" class="mt-1 block w-full"
                                    required />
                                @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <!-- Active checkbox -->
                            <div class="sm:col-span-3">
                                <div class="flex gap-3">
                                    <div class="flex h-6 shrink-0 items-center">
                                        <input id="is_active" wire:model="is_active" type="checkbox"
                                            @checked($is_active)
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

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <!-- Submit Button with Loader -->
                        <x-primary-button
                            class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                            x-data="{ loading: false }"
                            x-on:click="loading = true; $wire.updateOrganization().then(() => { loading = false; })"
                            x-bind:disabled="loading">
                            <span x-show="!loading">{{ __('Update') }}</span>
                            <span x-show="loading" class="flex justify-center items-center w-full">
                                <svg class="animate-spin h-4 w-4 text-gray-900" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z"></path>
                                </svg>
                            </span>
                        </x-primary-button>

                        <!-- Cancel Button -->
                        <x-secondary-button x-on:click="$dispatch('close-modal', 'edit-organization-modal')"
                            class="text-sm/6 font-semibold text-gray-900">{{ __('Cancel') }}</x-secondary-button>

                        <!-- Delete Button -->
                        <x-danger-button wire:click="deleteOrganization"
                            class="inline-flex items-center text-sm/6 font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                            <svg class="h-4 w-4 mr-1 text-white hover:text-red-100" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512">
                                <path fill="currentColor"
                                    d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                            </svg>
                            {{ __('Delete') }}
                        </x-danger-button>
                    </div>
            </form>
        </x-modal>




    </div>
</div>