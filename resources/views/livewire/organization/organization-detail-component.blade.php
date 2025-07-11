<div>
    <div class="overflow-hidden shadow-sm sm:rounded-lg max-w-screen-lg mx-auto sm:px-6 lg:px-8 py-6">
        <form wire:submit.prevent="createOrganization">
            <div class="relative isolate bg-white dark:bg-gray-800 px-6 py-12 sm:py-12 lg:px-8">
                <!-- Progress Indicator -->
                <div class="mb-8 border-b-2 pb-6 relative">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div
                                class="rounded-full h-8 w-8 flex items-center justify-center {{ $currentStep >= 1 ? 'bg-primary-md text-white' : 'bg-gray-200' }}">
                                1</div>
                            <div class="ml-2 dark:text-white">Organization Details</div>
                        </div>
                        {{-- <!-- Arrow between Step 1 and Step 2 -->
                        <svg class="h-6 w-12 absolute left-1/3 -translate-x-1/2" viewBox="0 0 48 24">
                            <path d="M0 12 L40 12 L30 6 M40 12 L30 18" stroke="currentColor" stroke-width="2"
                                fill="none" />
                        </svg> --}}
                        <div class="flex items-center">
                            <div
                                class="rounded-full h-8 w-8 flex items-center justify-center {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                                2</div>
                            <div class="ml-2 dark:text-white">Location</div>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Organization Details -->
                <div class="{{ $currentStep != 1 ? 'hidden' : '' }}">
                    <div class="pb-6">
                        <div class="text-gray-900 dark:text-gray-100">
                            {{ __('Organization Overview') }}
                            <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
                                Please provide your organization's basic information.
                            </p>
                        </div>
                    </div>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-input-label for="name" :value="__('*Organization Name')" />
                            <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full" />
                            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
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
                        <div class="sm:col-span-4">
                            <x-input-label for="email" :value="__('*Work Email')" />
                            <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full" />
                            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-6">
                            <x-input-label for="logo" :value="__('*Logo')" />
                            <input type="file" id="logo" wire:model="logo"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('logo.*')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <!-- Image Preview -->
                            <div class="mt-4 grid grid-cols-3 gap-2">
                                @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}"
                                            class="w-24 h-24 object-cover rounded">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Location -->
                <div class="{{ $currentStep != 2 ? 'hidden' : '' }}">
                    <div>
                        <div class="text-gray-900 dark:text-gray-100">
                            {{ __('Location Details') }}
                            <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">
                                Please provide your organization's location information.
                            </p>
                        </div>
                    </div>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <x-input-label for="address" :value="__('Street Address')" />
                            <x-text-input id="address" wire:model="address" type="text" class="mt-1 block w-full" />
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label for="country" :value="__('*Country')" />
                            <select wire:model.live="selectedCountry"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a Country</option>
                                @foreach (array_keys($countries) as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                            @error('selectedCountry') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="state" :value="__('*State/Province')" />
                            <select wire:model="state"
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                            @error('state') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="city" :value="__('*City')" />
                            <x-text-input id="city" wire:model="city" type="text" class="mt-1 block w-full" />
                            @error('city') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="pin" :value="__('*Zip/Postal Code')" />
                            <x-text-input id="pin" wire:model="pin" type="text" class="mt-1 block w-full" />
                            @error('pin') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>


                <!-- Navigation Buttons -->
                <div class="mt-6 flex items-center justify-between gap-x-6">
                    @if($currentStep > 1)
                        <x-secondary-button wire:click="previousStep" type="button">
                            Previous
                        </x-secondary-button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < $totalSteps)
                        <x-primary-button wire:click="nextStep" type="button">
                            Next
                        </x-primary-button>
                    @else
                        <x-primary-button>
                            Submit
                        </x-primary-button>
                    @endif
                </div>