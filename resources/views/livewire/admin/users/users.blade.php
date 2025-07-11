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
                                    {{ __('Manage Users') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Review and manage users as required.') }}
                                </p>
                            </div>
                            <div>
                                <x-secondary-button class="min-w-36 flex justify-center items-center"
                                    x-data="{ loading: false }"
                                    x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'import-users-modal'); loading = false }, 1000)"
                                    x-bind:disabled="loading">
                                    <!-- Button Text -->
                                    <span x-show="!loading">{{ __('Import Users') }}</span>
                                    <!-- Loader (Spinner) -->
                                    <span x-show="loading" class="flex justify-center items-center w-full">
                                        <svg class="animate-spin h-4 w-4 text-primary-md"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                                    <span x-show="!loading">{{ __('+ Add New User') }}</span>
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
                        </header>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-screen-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white  dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div
                class="p-6 bg-white  dark:bg-gray-800 border-b border-gray-600 dark:border-gray-700 text-sm dark:text-gray-400">
                <livewire:users-list />
            </div>
        </div>
    </div>
    <x-modal name="add-user-modal" width="w-100" height="h-auto" maxWidth="3xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('+ Add User') }}
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
                                <x-text-input id="phone" wire:model.lazy="phone" type="tel" maxlength="12"
                                    placeholder="123-456-7890" @blur="formatPhone($event)"
                                    class="block w-full rounded-none rounded-r-md" />

                            </div>
                            @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <!-- Role Dropdown -->
                        {{-- <div class="col-span-2">
                            <x-input-label for="role_id" :value="__('*Role')" />
                            <select id="role_id" name="role_id"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                wire:model.defer="role_id">>
                                <option value="1">{{ __('Super Admin') }}</option>
                                <option value="2">{{ __('Admin') }}</option>
                                <option value="3">{{ __('Staff') }}</option>
                            </select>
                        </div> --}}
                        <!-- Organization Dropdown (Initially hidden) -->
                        <div id="organization_dropdown" class="col-span-2">
                            <label for="organization" class="block text-sm font-medium">Organization</label>
                            <select id="organization" name="organization_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm" wire:model="organization_id">
                                <option value="">Select Organization</option>
                                @foreach (\App\Models\Organization::all() as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Location Dropdown (Initially hidden) -->
                        <div id="location_dropdown" class="col-span-2">
                            <x-input-label for="location_id" :value="__('Assign location')" />
                            <select id="location_id" wire:model="location_id" name="location_id"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a Location</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <x-primary-button class="text-sm/6 font-semibold text-gray-900">{{ __('Submit') }}</x-primary-button>
                <x-secondary-button x-on:click="$dispatch('close-modal', 'add-user-modal')"
                    class="text-sm/6 font-semibold text-gray-900">{{ __('Cancel') }}</x-secondary-button>
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

    <!-- Edit Modal -->
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
                    <!-- Name -->
                    <div class="sm:col-span-3">
                        <x-input-label for="name" :value="__('*Name')" />
                        <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full" required />
                        @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-3">
                        <x-input-label for="email" :value="__('*Email')" />
                        <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full" required />
                        @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-span-2">
                        <x-input-label for="phone" :value="__('Phone Number')" />
                        <div class="flex mt-1">
                            <span
                                class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md">+1</span>
                            <x-text-input id="phone" wire:model.lazy="phone" type="tel" maxlength="12"
                                placeholder="123-456-7890" @blur="formatPhone($event)"
                                class="block w-full rounded-none rounded-r-md" />

                        </div>
                        @error('phone') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role -->

                    {{-- <div class="col-span-2">
                        <x-input-label for="role" :value="__('*Role')" />
                        <select wire:model="role_id"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            required>
                            <option value="1">{{ __('Super Admin') }}</option>

                            <option value="2">{{ __('Admin') }}</option>
                            <option value="3">{{ __('Staff') }}</option>
                        </select>
                        @error('role_id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <!-- Organization Dropdown -->
                    <div class="col-span-2" x-show="$wire.role_id != 1">
                        <label for="organization" class="block text-sm font-medium">Organization</label>
                        <select id="edit_organization_id" name="organization_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm" wire:model="organization_id">
                            <option value="">Select Organization</option>
                            @foreach (\App\Models\Organization::all() as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <!-- Location Dropdown -->
                    <div class="col-span-2" x-show="$wire.role_id != 1">
                        <label for="location_id" class="block text-sm font-medium">Assign Location</label>
                        <select id="edit_location_id" name="location_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm" wire:model="location_id">
                            <option value="">Select a Location</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ $location->id == $location_id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Is Active Dropdown -->

                    <!-- Active checkbox -->
                    <div class="col-span-6">
                        <div class="flex gap-3">
                            <div class="flex h-6 shrink-0 items-center">
                                <input id="is_active" wire:model="is_active" @checked($is_active) type="checkbox"
                                    class="appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            </div>
                            <div class="text-sm/6">
                                <x-input-label for="is_active" :value="__('Is Active')" />
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('This feature is used to make users active or inactive') }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Update Button -->
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
                                    stroke-width="4">
                                </circle>
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
                                    stroke-width="4">
                                </circle>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Function to fetch locations for a given organization
                function fetchLocationsForEdit(organizationId, selectedLocationId = null) {
                    if (!organizationId) {
                        $('#edit_location_id').html('<option value="">Select a Location</option>'); // Reset locations
                        return;
                    }

                    $.ajax({
                        url: '{{ url('/get-locations') }}/' + organizationId,
                        type: 'GET',
                        success: function (data) {
                            let options = '<option value="">Select a Location</option>';
                            data.forEach(function (location) {
                                options += '<option value="' + location.id + '">' + location
                                    .address + ', ' + location.state + ', ' + location.country +
                                    '</option>';
                            });
                            $('#edit_location_id').html(options);

                            // Pre-select the user's location if editing an existing user
                            if (selectedLocationId) {
                                $('#edit_location_id').val(selectedLocationId);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error: ' + error);
                        }
                    });
                }

                // Trigger AJAX when organization is selected in edit modal
                $('#edit_organization_id').change(function () {
                    let selectedLocationId = $('#edit_location_id').attr(
                        'data-selected'); // Get existing location
                    fetchLocationsForEdit($(this).val(), selectedLocationId);
                });

                // When the edit modal is opened, fetch locations for the existing organization
                $('#edit-user-modal').on('show.bs.modal', function (event) {
                    let organizationId = $('#edit_organization_id').val(); // Get the current organization ID
                    let selectedLocationId = $('#edit_location_id').attr(
                        'data-selected'); // Get the preselected location ID

                    // Fetch locations for the current organization and pre-select the location
                    fetchLocationsForEdit(organizationId, selectedLocationId);
                });
            });
        </script>
    </x-modal>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <script>
        // When the organization is selected
        $('#organization').change(function () {
            var organizationId = $(this).val();

            // Hide location dropdown if no organization is selected
            if (organizationId) {
                $('#location_dropdown').show();
                fetchLocations(organizationId);
            } else {
                $('#location_dropdown').hide();
                $('#location_id').html('<option value="">Select a Location</option>'); // Clear locations
            }
        });

        // Fetch locations using AJAX
        function fetchLocations(organizationId) {
            $.ajax({
                url: '{{url("/get-locations")}}/' + organizationId,
                type: 'GET',
                success: function (data) {
                    // Populate the location dropdown with the fetched data
                    $('#location_id').html('<option value="">Select a Location</option>'); // Reset options
                    data.forEach(function (location) {
                        $('#location_id').append('<option value="' + location.id + '">' + location.address + ', ' + location.state + ', ' + location.country + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ' + error);
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
            const roleDropdown = document.getElementById('role_id');
            const organizationDropdown = document.getElementById('organization_dropdown');
            const locationDropdown = document.getElementById('location_dropdown');

            // Function to toggle dropdown visibility based on role selection
            function toggleDropdownVisibility(role) {
                if (role === '2' || role === '3') {
                    organizationDropdown.style.display = 'block';
                    locationDropdown.style.display = 'block';
                } else {
                    organizationDropdown.style.display = 'none';
                    locationDropdown.style.display = 'none';
                }
            }

            // Initially check the role and set visibility
            toggleDropdownVisibility(roleDropdown.value);

            // Listen for changes on the role dropdown
            roleDropdown.addEventListener('change', function () {
                toggleDropdownVisibility(this.value);
            });

            // If using a modal, reset on modal open
            // document.addEventListener('open-modal', function (event) {
            //     if (event.detail === 'add-user-modal') {
            //         roleDropdown.value = '1'; // Default to Super Admin
            //         toggleDropdownVisibility(roleDropdown.value); // Reset dropdowns
            //     }
            // });
        });
    </script>
</div>