<div class="ml-3 px-6 py-4 bg-white dark:bg-gray-800 rounded-lg border">
    <div class="flex justify-between items-center gap-2 pt-3 border-b-2 border-gray-900/10 pb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Patients') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Manage patients for your clinics') }}
            </p>
        </div>
        <!-- Add button -->
        <div class="flex items-center justify-center gap-3">
            @php
                $user = auth()->user();
                $role = $user->role;
            @endphp
            @if ($role?->hasPermission('add_patient') || $user->role_id <= 2)
                <x-secondary-button class="flex justify-center items-center" x-data="{ loading: false }"
                    x-on:click="loading = true; setTimeout(() => { $dispatch('open-modal', 'import-patient-modal'); loading = false }, 1000)"
                    x-bind:disabled="loading">
                    <!-- Button Text -->
                    <span x-show="!loading">
                        <span class="flex justify-center items-center gap-2">
                            <svg width="16px" height="16px" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="1"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                </g>
                                <g id="SVGRepo_iconCarrier">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: currentColor;
                                            }
                                        </style>
                                    </defs>
                                    <title></title>
                                    <g id="xxx-word">
                                        <path class="cls-1"
                                            d="M325,105H250a5,5,0,0,1-5-5V25a5,5,0,1,1,10,0V95h70a5,5,0,0,1,0,10Z">
                                        </path>
                                        <path class="cls-1"
                                            d="M325,154.83a5,5,0,0,1-5-5V102.07L247.93,30H100A20,20,0,0,0,80,50v98.17a5,5,0,0,1-10,0V50a30,30,0,0,1,30-30H250a5,5,0,0,1,3.54,1.46l75,75A5,5,0,0,1,330,100v49.83A5,5,0,0,1,325,154.83Z">
                                        </path>
                                        <path class="cls-1"
                                            d="M300,380H100a30,30,0,0,1-30-30V275a5,5,0,0,1,10,0v75a20,20,0,0,0,20,20H300a20,20,0,0,0,20-20V275a5,5,0,0,1,10,0v75A30,30,0,0,1,300,380Z">
                                        </path>
                                        <path class="cls-1" d="M275,280H125a5,5,0,1,1,0-10H275a5,5,0,0,1,0,10Z">
                                        </path>
                                        <path class="cls-1" d="M200,330H125a5,5,0,1,1,0-10h75a5,5,0,0,1,0,10Z">
                                        </path>
                                        <path class="cls-1"
                                            d="M325,280H75a30,30,0,0,1-30-30V173.17a30,30,0,0,1,30-30h.2l250,1.66a30.09,30.09,0,0,1,29.81,30V250A30,30,0,0,1,325,280ZM75,153.17a20,20,0,0,0-20,20V250a20,20,0,0,0,20,20H325a20,20,0,0,0,20-20V174.83a20.06,20.06,0,0,0-19.88-20l-250-1.66Z">
                                        </path>
                                        <path class="cls-1"
                                            d="M168.48,217.48l8.91,1a20.84,20.84,0,0,1-6.19,13.18q-5.33,5.18-14,5.18-7.31,0-11.86-3.67a23.43,23.43,0,0,1-7-10,37.74,37.74,0,0,1-2.46-13.87q0-12.19,5.78-19.82t15.9-7.64a18.69,18.69,0,0,1,13.2,4.88q5.27,4.88,6.64,14l-8.91.94q-2.46-12.07-10.86-12.07-5.39,0-8.38,5t-3,14.55q0,9.69,3.2,14.63t8.48,4.94a9.3,9.3,0,0,0,7.19-3.32A13.25,13.25,0,0,0,168.48,217.48Z">
                                        </path>
                                        <path class="cls-1"
                                            d="M179.41,223.15l9.34-2q1.68,7.93,12.89,7.93,5.12,0,7.87-2a6.07,6.07,0,0,0,2.75-5,7.09,7.09,0,0,0-1.25-4q-1.25-1.85-5.35-2.91l-10.2-2.66a25.1,25.1,0,0,1-7.73-3.11,12.15,12.15,0,0,1-4-4.9,15.54,15.54,0,0,1-1.5-6.76,14,14,0,0,1,5.31-11.46q5.31-4.32,13.59-4.32a24.86,24.86,0,0,1,12.29,3,13.56,13.56,0,0,1,6.89,8.52l-9.14,2.27q-2.11-6.05-9.84-6.05-4.49,0-6.86,1.88a5.83,5.83,0,0,0-2.36,4.77q0,4.57,7.42,6.41l9.06,2.27q8.24,2.07,11.05,6.11a15.29,15.29,0,0,1,2.81,8.93,14.7,14.7,0,0,1-5.92,12.36q-5.92,4.51-15.33,4.51a28,28,0,0,1-13.89-3.32A16.29,16.29,0,0,1,179.41,223.15Z">
                                        </path>
                                        <path class="cls-1"
                                            d="M250.31,236h-9.77L224.1,182.68h10.16l12.23,40.86L259,182.68h8Z">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            {{ __('Import') }}
                        </span>
                    </span>

                    <!-- Loader (Spinner) -->
                    <span x-show="loading" class="flex justify-center items-center w-full">
                        <svg class="animate-spin h-5 w-5 text-primary-md" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-50" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                            </path>
                        </svg>
                    </span>
                </x-secondary-button>
                <x-primary-button wire:click="addPatient" class="px-4 py-2">
                    {{ __('+ Add Patient Info') }}
                </x-primary-button>
            @endif
        </div>
    </div>

    <div class="pt-3 text-xs">
        <livewire:tables.organization.patients-list />
    </div>

   @include('livewire.organization.patients.modals.patient-modal')
    <!-- Confirmation Modal -->
    <x-modal name="delete-patient-modal" width="w-100" height="h-auto" maxWidth="3xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{  __('Are you sure want to delete chart number ' . $chartnumber . ' ?') }}
            </h2>
        </header>
        <div class="mt-6 flex items-center justify-end gap-x-6 px-6 pb-4">
            <x-secondary-button x-on:click="$dispatch('close-modal', 'patient-modal')"
                class="text-sm/6 font-semibold text-gray-900">{{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                wire:click="confirmdeletePatient">
                {{ __('Delete') }}
            </x-primary-button>
        </div>
    </x-modal>

    <x-modal name="import-patient-modal" width="w-100" height="h-auto" maxWidth="4xl">
        <header class="p-3">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Import Patients list') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Upload a CSV file to import multiple patient\'s data at once.') }}
            </p>
        </header>
        <form action="{{ route('import.patients') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6 p-3">
                <div class="border-b border-gray-900/10 pb-6">
                    <div class="grid grid-cols-6">
                        <!-- CSV Template Example -->
                        <div class="col-span-3 p-3">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('CSV Format') }}
                            </h3>
                            <div class="mt-2">
                                <x-secondary-button type="button" wire:click="downloadSampleCsv">
                                    {{ __('Download Sample CSV') }}
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>
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
            </div>
            <div class="flex justify-end gap-4 mt-6 p-3" x-data="{ loading: false }">
                <x-primary-button typr="submit" class="min-w-24 flex justify-center items-center">Import
                </x-primary-button>
                <x-secondary-button
                    x-on:click="$dispatch('close-modal', 'import-products-modal')">{{ __('Cancel') }}</x-secondary-button>
            </div>
        </form>
    </x-modal>
    <!-- Notifications Container -->
    <div class="fixed top-24 right-4 z-50 space-y-2">
        @foreach ($notifications as $notification)
            <div wire:key="{{ $notification['id'] }}" x-data="{ show: true }" x-init="setTimeout(() => {
                            show = false;
                            $wire.removeNotification('{{ $notification['id'] }}');
                        }, 3000)" x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
                class="{{ $notification['type'] === 'success' ? 'text-white bg-green-400' : 'text-white bg-red-400' }} border-l-4 py-3 px-4 rounded-lg shadow-lg">
                <p>{{ $notification['message'] }}</p>
            </div>
        @endforeach
    </div>
</div>