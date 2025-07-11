<!-- Patient Modal (used for both add and edit) -->
<x-modal name="patient-modal" width="w-100" height="h-auto" maxWidth="6xl">
    <header class="p-3">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $editMode ? __('Edit Patient Information') : __('Add Patient Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $editMode ? __('Update information for existing patient.') : __('Add information regarding new Patient.') }}
        </p>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure that all your details are accurate before proceeding.') }}
        </p>
    </header>
    <form wire:submit.prevent="{{ $editMode ? 'updatePatient' : 'createPatient' }}">
        <div class="space-y-3">
            <div class="border-b border-gray-900/10 pb-12 px-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <p class="sm:col-span-6 border-b mb-2 text-gray-600 dark:text-gray-400">
                        {{  __('Patient\'s Information') }}
                    </p>
                    <div class="sm:col-span-2">
                        <x-input-label for="chartnumber" :value="__('*Chart Number')" />
                        <x-text-input id="chartnumber" wire:model="chartnumber" type="text" class="mt-1 block w-full"
                            required />
                        @error('chartnumber')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="ins_type" :value="__('INS Type')" />
                        <x-text-input id="ins_type" wire:model="ins_type" type="text" class="mt-1 block w-full"
                            required />
                        @error('ins_type')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="provider" :value="__('Provider')" />
                        <x-text-input id="provider" wire:model="provider" type="text" class="mt-1 block w-full"
                            required />
                        @error('provider')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="icd" :value="__('ICD')" />
                        <x-text-input id="icd" wire:model="icd" type="text" class="mt-1 block w-full" required />
                        @error('icd')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-3 hidden">
                        <x-input-label for="account_number" :value="__('Account #')" />
                        <x-text-input id="account_number" wire:model="account_number" type="text"
                            class="mt-1 block w-full" required />
                        @error('account_number')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <p class="sm:col-span-6 border-b mb-2 text-gray-600 dark:text-gray-400">
                        {{  __('Drug\'s details') }}
                    </p>
                    <div class="sm:col-span-1">
                        <x-input-label for="date_given" :value="__('Date given')" />

                        <x-text-input id="date_given" wire:model="date_given" class="mt-1 block w-full"
                            required readonly/>
                        @error('date_given')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="sm:col-span-1">
                        <x-input-label for="pa_expires" :value="__('PA Expires')" />
                        <x-text-input id="pa_expires" wire:model="pa_expires" type="date" class="mt-1 block w-full"
                            required />
                        @error('pa_expires')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="sm:col-span-3">
                        <x-input-label for="drug" :value="__('Drug')" />
                        <x-text-input id="drug" wire:model="drug" type="text" class="mt-1 block w-full" required />
                        @error('drug')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="dose" :value="__('Dose')" />
                        <x-text-input id="dose" wire:model="dose" type="text" class="mt-1 block w-full" required />
                        @error('dose')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="frequency" :value="__('Frequency')" />
                        <x-text-input id="frequency" wire:model="frequency" type="text" class="mt-1 block w-full"
                            required />
                        @error('frequency')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <p class="sm:col-span-6 border-b mb-2 text-gray-600 dark:text-gray-400">
                        {{ __('Payment information') }}
                    </p>
                    <div class="sm:col-span-1">
                        <x-input-label for="paid" :value="__('INS Paid')" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $organization?->currency }}</span>
                            </div>
                            <x-text-input id="paid" wire:model.live="paid" type="number" step="0.01"
                                class="mt-1 block w-full pl-8" required />
                        </div>
                        @error('paid')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-1">
                        <x-input-label for="our_cost" :value="__('Our Cost')" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $organization?->currency }}</span>
                            </div>
                            <x-text-input id="our_cost" wire:model.live="our_cost" type="number" step="0.01"
                                class="mt-1 block w-full pl-8" required />
                        </div>
                        @error('our_cost')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-1">
                        <x-input-label for="pt_copay" :value="__('PT Paid')" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $organization?->currency }}</span>
                            </div>
                            <x-text-input id="pt_copay" wire:model.live="pt_copay" type="number" step="0.01"
                                class="mt-1 block w-full pl-8" required />
                        </div>

                        @error('pt_copay')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="sm:col-span-1">
                        <x-input-label for="profit" :value="__('Profit')" />
                        <div class="mt-1 flex items-center">
                            <div
                                class="bg-gray-100 px-3 py-2 rounded-md w-full text-gray-700 dark:text-gray-300 relative">
                                {{ $organization?->currency }}{{ number_format($profit, 2) }}
                                <div wire:loading wire:target="paid, our_cost, pt_copay"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                    <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @error('profit')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <p class="sm:col-span-6 border-b mb-2 text-gray-600 dark:text-gray-400">
                        {{  __('Contact Information') }}
                    </p>
                    <!-- Address -->
                    <div class="col-span-full">
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input id="address" wire:model="address" type="text" class="mt-1 block w-full"
                            required />
                        @error('address')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Country -->
                    <div class="sm:col-span-2">
                        <x-input-label for="country" :value="__('*Country')" />
                        <select wire:model.live="selectedCountry" class="w-full border-gray-300 rounded-md shadow-sm"
                            required>
                            <option value="">Select a Country</option>
                            @foreach (array_keys($countries) as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                        @error('selectedCountry')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- State -->
                    @if(!empty($selectedCountry))
                        <div class="sm:col-span-2">
                            <x-input-label for="state" :value="__('*State/Province')" />
                            <select wire:model="state" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Select a State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                            @error('state')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif


                    <!-- City -->
                    {{-- <div class="sm:col-span-2 sm:col-start-1">
                        <x-input-label for="city" :value="__('*City')" />
                        <x-text-input id="city" wire:model="city" type="text" class="mt-1 block w-full" required />
                        @error('city')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <!-- Pin -->
                    <div class="sm:col-span-1">
                        <x-input-label for="pin_code" :value="__('*Zip/Postal Code')" />
                        <x-text-input id="pin_code" wire:model="pin_code" type="text" class="mt-1 block w-full"
                            required />
                        @error('pin_code')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6 px-6 pb-4">
            <x-secondary-button x-on:click="$dispatch('close-modal', 'patient-modal')"
                class="text-sm/6 font-semibold text-gray-900">{{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="min-w-24 flex justify-center items-center text-sm/6 font-semibold text-gray-900"
                x-data="{ loading: false }"
                x-on:click="loading = true; $wire.{{ $editMode ? 'updatePatient' : 'createPatient' }}().then(() => { loading = false; })"
                x-bind:disabled="loading">
                <!-- Button Text -->
                <span x-show="!loading">{{ $editMode ? __('Update') : __('Submit') }}</span>

                <!-- Loader (Spinner) -->
                <span x-show="loading" class="flex justify-center items-center w-full">
                    <svg class="animate-spin h-4 w-4 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 0 6.477 0 12h4z">
                        </path>
                    </svg>
                </span>
            </x-primary-button>
        </div>
    </form>
</x-modal>