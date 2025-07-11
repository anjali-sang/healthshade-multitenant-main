<div>
    <div class="sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg w-full space-y-6 mb-6">
        <div class=" bg-white dark:bg-gray-800 sm:rounded-lg">
            <section class="w-full">
                <header class="flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-3">
                    @php
                        $org = auth()->user()->organization;
                        $subs = $org->subscription;
                        $maxUsers = $subs->max_users;
                        $activeUsers = $org->users()->where('is_active', true)->count() ?? 0;
                    @endphp
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Manage Users') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Review and manage your users details here below.') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Maximum ') }}{{ $maxUsers }}{{ __(' users allowed under your subscription model.') }}
                        </p>
                    </div>
                    <div>
                        @if ($activeUsers < $maxUsers)
                            <div>
                                <x-secondary-button class="min-w-36 flex justify-center items-center"
                                    x-data="{ loading: false }"
                                    x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'import-users-modal'); loading = false }, 1000)"
                                    x-bind:disabled="loading">
                                    <!-- Button Text -->
                                    <span x-show="!loading">{{ __('Import Users') }}</span>
                                    <!-- Loader (Spinner) -->
                                    <span x-show="loading" class="flex justify-center items-center w-full">
                                        <svg class="animate-spin h-4 w-4 text-primary-md" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                                            </path>
                                        </svg>
                                    </span>
                                </x-secondary-button>
                                <x-primary-button class="min-w-36 flex justify-center items-center"
                                    x-data="{ loading: false }"
                                    x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'add-user-modal'); loading = false }, 1000)"
                                    x-bind:disabled="loading">
                                    <!-- Button Text -->
                                    <span x-show="!loading">{{ __('+ Add User') }}</span>
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
                        @else
                            <p class="text-red-600 font-bold">
                                {{ __('Max limit exceeded for Users. Please upgrade your plan.') }}
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
                <livewire:tables.user-list />
            </div>
        </div>
    </div>

    <!-- Notifications Container -->
    <div class="fixed top-24 right-4 z-50 space-y-2">
        @foreach($notifications as $notification)
            <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-init="
                                                                                    setTimeout(() => {
                                                                                        show = false;
                                                                                        $wire.removeNotification('{{ $notification['id'] }}');
                                                                                    }, 3000)
                                                                                " x-show="show"
                x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
                class="{{ $notification['type'] === 'success' ? 'border-green-800 text-green-800  bg-green-300' : 'bg-red-300 border-red-800 text-red-800' }} border-l-4 x-6 py-6 px-4  shadow-lg bg-white dark:bg-gray-700">
                <p>{{ $notification['message'] }}</p>
            </div>
        @endforeach
    </div>

    <x-modal name="add-user-modal" width="w-100" height="h-auto" maxWidth="3xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Add New User') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Create a new User and those fields marked as * are compulsory.') }}
            </p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Ensure that all your details are accurate before proceeding.') }}
            </p>
        </header>
        <form wire:submit.prevent="createUser">
            <div class="space-y-12 p-3">
                <div class="border-b border-gray-900/10 pb-12 px-12">
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

                        <div class="col-span-2">
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <div class="flex mt-1">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">+1</span>
                                <x-text-input id="phone" wire:model.lazy="phone" type="tel" maxlength="10"
                                    placeholder="123-456-7890" @blur="formatPhone($event)"
                                    class="block w-full rounded-none rounded-r-md" />

                            </div>
                            @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="role_id" :value="__('*Role')" />
                            <select wire:model="role_id"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="2">{{ __('Admin') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2">
                            <x-input-label for="location_id" :value="__('Assign location')" />
                            <select wire:model.live="location_id"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a Location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <x-primary-button
                    class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                    x-data="{ loading: false }"
                    x-on:click="loading = true; $wire.createUser().then(() => { loading = false; })"
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


                <x-secondary-button x-on:click="$dispatch('close-modal', 'add-user-modal')"
                    class="text-sm/6 font-semibold text-gray-900">{{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </form>

    </x-modal>

    <x-modal name="import-users-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Import Users') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Upload a CSV file to import multiple users. Ensure the organization column is specified.') }}
            </p>
        </header>
        <form action="{{ route('import.users') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6 p-3">
                <div class="border-b border-gray-900/10 pb-6">

                </div>

                <!-- CSV File Upload -->
                <div class="mt-4">
                    <x-input-label for="csv_file" :value="__('*CSV File')" />
                    <input type="file" name="csv_file" id="csv_file"
                        class="mt-1 block w-full border rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        accept=".csv">
                    @error('csvFile')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- CSV Template Example -->
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('CSV Format Example') }}</h3>
                    <div class="mt-2">
                        <x-secondary-button type="button" wire:click="downloadSampleCsv">
                            {{ __('Download Sample CSV') }}
                        </x-secondary-button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6 p-3" x-data="{ loading: false }">
                <x-primary-button type="submit" class="min-w-24 flex justify-center items-center">
                    Import
                </x-primary-button>
                <x-secondary-button x-on:click="$dispatch('close-modal', 'import-users-modal')">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="edit-user-modal" width="w-100" height="h-auto" maxWidth="3xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Edit User details') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Edit User information and those fields marked as * are compulsory.') }}
            </p>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Ensure that all your details are accurate before proceeding.') }}
            </p>
        </header>
        <form wire:submit.prevent="updateUserData">
            @if (session('user-update-success'))
                <div class="max-w-screen-2xl mx-auto mt-4 p-4 mb-4 text-sm text-green-900 rounded-lg bg-green-200 dark:bg-gray-800 border dark:text-green-500 dark:border-green-500"
                    role="alert">
                    {{ session('user-update-success') }}
                </div>
            @endif
            @if (session('user-update-error'))
                <div class="max-w-screen-2xl mx-auto mt-4 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    {{ session('user-update-error') }}
                </div>
            @endif
            <div class="space-y-12 p-3 border-gray-900/10 border-b dark:border-slate-400 pb-6">
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
                        <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full" required />
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="phone" :value="__('Phone Number')" />
                        <div class="flex mt-1">
                            <span
                                class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">+1</span>
                            <x-text-input id="phone" wire:model.lazy="phone" type="tel" maxlength="10"
                                placeholder="123-456-7890" @blur="formatPhone($event)"
                                class="block w-full rounded-none rounded-r-md" />

                        </div>
                        @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="role" :value="__('*Role')" />
                        <select wire:model="role_id"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            required>
                            <option value="2">{{ __('Admin') }}</option>
                            @foreach ($roles as $role)
                                <option value={{ $role->id }}>{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="location_id" :value="__('Assign location')" />
                        <select wire:model.live="location_id"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Select a Locationn</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-span-2 flex items-center justify-end">
                    <x-primary-button
                        class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                        x-data="{ loading: false }"
                        x-on:click="loading = true; $wire.updateUserData().then(() => { loading = false; })"
                        x-bind:disabled="loading">
                        <!-- Button Text -->
                        <span x-show="!loading">{{ __('Update') }}</span>

                        <!-- Loader (Spinner) -->
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
                </div>
            </div>
        </form>
        <form wire:submit.prevent="resetPassword">
            @if (session('user-password-update-success'))
                <div class="max-w-screen-2xl mx-auto mt-4 p-4 mb-4 text-sm text-green-900 rounded-lg bg-green-200 dark:bg-gray-800 border dark:text-green-500 dark:border-green-500"
                    role="alert">
                    {{ session('user-password-update-success') }}
                </div>
            @endif
            @if (session('user-update-error'))
                <div class="max-w-screen-2xl mx-auto mt-4 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    {{ session('user-update-error') }}
                </div>
            @endif
            <div class="space-y-12 p-3 border-gray-900/10 border-b dark:border-slate-400 pb-6">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <x-input-label for="password" :value="__('*Password')" />
                        <x-text-input id="password" wire:model="password" type="password" class="mt-1 block w-full"
                            required />
                        @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-3">
                        <x-input-label for="password_confirmation" :value="__('*Confirm Password')" />
                        <x-text-input id="password_confirmation" wire:model="password_confirmation" type="password"
                            class="mt-1 block w-full" required />
                        @error('password_confirmation')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-span-2 flex items-center justify-end">
                    <x-primary-button
                        class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                        x-data="{ loading: false }"
                        x-on:click="loading = true; $wire.resetPassword().then(() => { loading = false; })"
                        x-bind:disabled="loading">
                        <!-- Button Text -->
                        <span x-show="!loading">{{ __('Update') }}</span>

                        <!-- Loader (Spinner) -->
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
                </div>
            </div>
        </form>
        <div class="p-3 flex justify-end items-center gap-x-3">
            <x-secondary-button class="text-sm/6 font-semibold text-gray-900"
                x-on:click="$dispatch('close-modal', 'edit-user-modal')">{{ __('Close') }}</x-secondary-button>

            <x-danger-button wire:click="deleteUser"
                class="inline-flex items-center text-sm/6 font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                <svg class="h-4 w-4 mr-1 text-white hover:text-red-100" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512">
                    <path fill="currentColor"
                        d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z" />
                </svg>
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </x-modal>
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